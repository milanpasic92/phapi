<?php

namespace Phapi\Application;

use Monolog\Handler\MissingExtensionException;
use Monolog\Handler\SlackHandler;
use Phalcon\DI;
use Phalcon\Di\Injectable;

class Logger extends Injectable
{

    /* Change $logger to $loggers[] where each will be loaded via config and can have different reporting settings */
    protected \Monolog\Logger $logger;

    public function __construct()
    {
        $di = DI::getDefault();
        $config = $di->get('config');

        $this->logger = new \Monolog\Logger(PROJECT_NAME . '_' . APPLICATION_ENV);
        try {
            $slackHandler = new SlackHandler(
                $config->monolog->slack,
                $config->monolog->slackChannel,
                $config->monolog->slackUsername,
                true,
                'abacus',
                \Monolog\Logger::INFO
            );
            $this->logger->pushHandler($slackHandler);
        } catch (MissingExtensionException $e) {
            //
        }
    }

    public function log($data)
    {
        if (!isset($data['errors'])) {
            $this->logger->info(json_encode($data));
        } else {
            $this->logger->error(json_encode($data));
        }
    }

}