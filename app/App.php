<?php

namespace Phapi;

use Phalcon\Config;
use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Db\Profiler;
use Phalcon\DI\FactoryDefault;
use Phalcon\Events\Manager;
use Phalcon\Loader;
use Phalcon\Mvc\Micro;
use Phalcon\Registry;
use Phapi\Application\ACL;
use Phapi\Application\AuthMiddleware;
use Phapi\Application\ConfigProvider;
use Phapi\Application\ContentNegotiationMiddleware;
use Phapi\Application\ErrorHandler;
use Phapi\Application\Logger;
use Phapi\Application\Rest;
use Phapi\Exceptions\ApiException;
use Phapi\Exceptions\BaseException;
use Phapi\Routes\Routes;

class App
{
    protected Micro $app;
    protected Config $config;

    public function __construct()
    {
        if (!defined('ROOT')) {
            define('ROOT', dirname(__DIR__));
        }
        if (!defined('APPLICATION_ENV')) {
            define('APPLICATION_ENV', getenv('APPLICATION_ENV'));
        }
        if (!defined('PROJECT_NAME')) {
            define('PROJECT_NAME', getenv('COMPOSE_PROJECT_NAME'));
        }

        require_once ROOT . '/vendor/autoload.php';
        ErrorHandler::run();
    }

    protected function registerNamespaces()
    {
        $loader = new Loader();

        $namespaces = [];
        foreach ($this->config->namespaces as $namespace => $directory) {
            $namespaces[$namespace] = realpath(ROOT . $directory);
        }

        $loader->registerNamespaces($namespaces);
        $loader->register();

        return $loader;
    }

    /**
     * This is just an example.
     * Best way to use it to overwrite run() method and init your own $di
     */
    public function run()
    {
        $di = new FactoryDefault();

        $configProvider = new ConfigProvider();
        $this->config = $configProvider->get();

        $this->registerNamespaces();

        $registry = new Registry();
        $di->setShared('registry', $registry);

        $di->setShared('config', $this->config);
        $di->setShared("rest", new Rest());
        $di->setShared('logger', new Logger());
        $di->setShared('acl', new ACL());

        $di->setShared('repo', function ($repo, $model){
            $repoClass = "\Phapi\Repository\\$repo";
            return new $repoClass($model);
        });

        try {
            $this->app = new Micro();
            $this->app->setDI($di);

            $this->resolveProfiler($di);

            $routes = new Routes($this->app);
            $routes->init();

            $this->setDbConnection($di);
            $this->initEventsManager($di);

            $this->app->after(function () use ($di) {
                $di->get('rest')->sendResponse($this->app->getReturnedValue());
            });

            // Processing request
            $this->app->handle($di->get('request')->getURI());
        } catch (BaseException $e) {
            $e->handle();
        } catch (ApiException $e) {
            $e->handle();
        }
    }

    protected function resolveProfiler($di)
    {
        $profilerEnabled = false;
        if ($this->config->profilerEnabled == 1) {
            $profilerEnabled = true;
        }
        if ($di->get('rest')->request->getQuery('profiler_enabled') == 1) {
            $profilerEnabled = true;
        }

        $di->get('registry')->set('profilerEnabled', $profilerEnabled);
    }

    protected function setDbConnection($di)
    {
        $di->set('profiler', function () {
            return new Profiler();
        }, true);
        $di->setShared("db", function () use ($di) {
            $config = $di->get('config');

            $connection = new Mysql(
                [
                    "host" => $config->database->host,
                    "username" => $config->database->username,
                    "password" => $config->database->password,
                    "dbname" => $config->database->dbname,
                ]
            );

            if ($di->get('registry')->get('profilerEnabled')) {
                $manager = new Manager();
                $profiler = $di->getProfiler();

                $manager->attach(
                    'db',
                    function ($event, $connection) use ($profiler) {
                        if ($event->getType() === 'beforeQuery') {
                            $profiler->startProfile(
                                $connection->getSQLStatement(),
                            );
                        }

                        if ($event->getType() === 'afterQuery') {
                            $profiler->stopProfile();
                        }
                    }
                );
                $connection->setEventsManager($manager);
            }

            return $connection;
        });
    }

    protected function initEventsManager($di)
    {

        $eventsManager = new Manager();

        $eventsManager->attach('micro:beforeExecuteRoute', new ContentNegotiationMiddleware());
        $eventsManager->attach('micro:beforeExecuteRoute', new AuthMiddleware($this->app));

        $this->app->setEventsManager($eventsManager);
    }
}
