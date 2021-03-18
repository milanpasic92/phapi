<?php

namespace Phapi\Application;

use Phalcon\Events\Event;
use Phalcon\Logger;

class Profiler{

    private \Phalcon\Db\Profiler $profiler;
    private Logger $logger;

    /**
     * Creates the profiler and starts the logging
     */
    public function __construct()
    {
        $this->profiler = new \Phalcon\Db\Profiler();
        $this->logger   = new Logger('../apps/logs/db.log');
    }

    /**
     * This is executed if the event triggered is 'beforeQuery'
     * @param Event $event
     * @param $connection
     */
    public function beforeQuery(Event $event, $connection)
    {
        $this->profiler->startProfile(
            $connection->getSQLStatement()
        );
    }

    /**
     * This is executed if the event triggered is 'afterQuery'
     * @param Event $event
     * @param $connection
     */
    public function afterQuery(Event $event, $connection)
    {
        $this->logger->log(
            $connection->getSQLStatement(),
            Logger::INFO
        );

        $this->profiler->stopProfile();
    }
}