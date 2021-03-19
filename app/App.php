<?php

namespace Phapi;

use Phapi\Application\ConfigProvider;
use Phapi\Application\ErrorHandler;
use Phapi\Application\Logger;
use Phapi\Application\Profiler;
use Phapi\Application\Rest;
use Phapi\Routes\Routes;
use Phapi\Exceptions\BaseException;
use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Events\Manager;

if(!defined('ROOT')) { define('ROOT', dirname(__DIR__));}
if(!defined('APPLICATION_ENV')) { define('APPLICATION_ENV', getenv('APPLICATION_ENV'));}
if(!defined('PROJECT_NAME')) { define('PROJECT_NAME', getenv('COMPOSE_PROJECT_NAME'));}

class App
{
    private \Phalcon\Mvc\Micro $app;
    private \Phalcon\Config $config;

    public function __construct()
    {
        $this->autoload();

        $configProvider = new ConfigProvider();
        $this->config = $this->config = $configProvider->get();

        $this->registerNamespaces();
        ErrorHandler::run();
    }

    public function run()
    {
        $di = new \Phalcon\DI\FactoryDefault();

        $registry = new \Phalcon\Registry();
        $di->setShared('registry', $registry);

        $di->setShared('config', $this->config);
        $di->setShared("rest", new Rest());
        $di->setShared('logger', new Logger());
        $di->setShared("db", $this->setDbConnection());

        $this->initEventsManager();

        try{
            $this->app = new \Phalcon\Mvc\Micro();
            $this->app->setDI($di);

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
        require_once ROOT . '/vendor/autoload.php';
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
