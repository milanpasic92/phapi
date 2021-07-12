<?php

namespace Phapi\Application;

use Monolog\Handler\MissingExtensionException;
use Monolog\Handler\SlackHandler;
use Monolog\Handler\LogglyHandler;
use Phalcon\DI;
use Phalcon\Di\Injectable;

class Logger extends Injectable
{
    protected \Monolog\Logger $logger;
    protected string $appName;

    public function __construct(string $appName)
    {
        $di = DI::getDefault();
        $config = $di->get('config');
        $this->appName = $appName;

        $this->logger = new \Monolog\Logger(PROJECT_NAME . '_' . APPLICATION_ENV);
        try {
            if(!empty($config->monolog->slack)) {
                $slackHandler = new SlackHandler(
                    $config->monolog->slack,
                    $config->monolog->slackChannel,
                    $config->monolog->slackUsername,
                    true,
                    'abacus',
                    \Monolog\Logger::INFO
                );
                $this->logger->pushHandler($slackHandler);
            }

            if(!empty($config->monolog->loggly)) {
                $slackHandler = new LogglyHandler(
                    $config->monolog->loggly,
                    \Monolog\Logger::INFO
                );
                $this->logger->pushHandler($slackHandler);
            }
        } catch (MissingExtensionException $e) {
            ////
        }
    }

    public function log($data)
    {
        $data = array_merge([
            'app_stack' => PROJECT_NAME,
            'app_environment' => APPLICATION_ENV,
            'app_name' => $this->appName
        ], $data);
        if (!isset($data['errors'])) {
            $this->logger->info(json_encode($data));
        } else {
            $this->logger->error(json_encode($data));
        }
    }

}