<?php

namespace Phapi;

use Phapi\Application\Logger;
use Phapi\Application\Profiler;
use Phapi\Application\Rest;
use Phapi\Exceptions\BaseException;
use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Events\Manager;
use Phalcon\Http\Request;
use Phapi\Routes\Routes;

class App
{
    /* TODO: caching layer with annotations https://docs.phalcon.io/4.0/en/annotations */

    private \Phalcon\Mvc\Micro $app;
    private \Phalcon\Config $config;

    public function __construct(\Phalcon\Config $config)
    {
        $this->config = $config;
    }

    public function run()
    {
        include ROOT . '/config/error_handler.php';
        $di = new \Phalcon\DI\FactoryDefault();

        $this->autoload();
        $this->registerNamespaces();

        $registry = new \Phalcon\Registry();
        $di->setShared('registry', $registry);

        $di->setShared('config', $this->config);
        $di->setShared('response', $this->response());
        $di->setShared('request', new Request());
        $di->set("rest", new Rest());
        $di->set("db", $this->setDbConnection());
        $di->setShared('logger', new Logger());

        $this->initEventsManager();

        try{
            $this->app = new \Phalcon\Mvc\Micro();
            $this->app->setDI($di);

            //$this->registerRoutes();
            $routes = new Routes($this->app);
            $routes->init();

            $this->app->after(function () use ($di) {
                $di->get('rest')->sendResponse($this->app->getReturnedValue());
            });

            // Processing request
            $this->app->handle($di->get('request')->getURI());
        }
        catch (BaseException $e){
            $e->handle();
        }
    }

    private function autoload(){
        require_once ROOT . '/../vendor/autoload.php';
    }

    private function registerNamespaces(){
        $loader = new \Phalcon\Loader();

        $namespaces = [];
        foreach ($this->config->namespaces as $namespace => $directory){
            $namespaces[$namespace] = realpath(ROOT . $directory);
        }

        $loader->registerNamespaces($namespaces);
        $loader->register();

        return $loader;
    }

    private function response()
    {
        $response = new \Phalcon\Http\Response();
        $response->setContentType('application/json', 'utf-8');

        return $response;
    }

    private function setDbConnection(){
        return new Mysql(
            [
                "host"     => $this->config->database->host,
                "username" => $this->config->database->username,
                "password" => $this->config->database->password,
                "dbname"   => $this->config->database->dbname,
            ]
        );
    }

    private function initEventsManager(){
        $eventsManager = new Manager();
        $profiler = new Profiler();

        $eventsManager->attach('db', $profiler);
    }
}
