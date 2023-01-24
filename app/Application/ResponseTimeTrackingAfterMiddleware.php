<?php

namespace Phapi\Application;

use Phalcon\DI;

class ResponseTimeTrackingAfterMiddleware
{
    public function afterExecuteRoute()
    {
        $di = DI::getDefault();
        $data = $di->get('registry')->get('phapi.responseTimeInfo');
        $data['data'] = [];
        if($di->has('user')) {
            $data['data']['tracking_id'] = $di->get('user')->data['id'];
        }
        $data['endedAt'] = hrtime(true);
        $lasted = ($data['endedAt'] - $data['startedAt'])  / 1e+6; // milliseconds
        // todo: add field for (int) status code
        // todo: add field for (string) http_method

        $db = $di->get('rtt_db');

        // @ToDo: this should be moved to be run on startup
        $test = $db->execute("
            CREATE TABLE IF NOT EXISTS `requests` (
              `id` INT NOT NULL AUTO_INCREMENT,
              `url` varchar(1024) NOT NULL,
              `timestamp` DATE NOT NULL,
              `duration` DOUBLE(10,2) NOT NULL,
              `data` LONGTEXT  NULL,
              PRIMARY KEY (`id`)
            );
        ");

        $insert = $db->execute("
            INSERT INTO `requests` SET 
              `url`=?,
              `timestamp`=NOW(),
              `duration`=?,
              `data`=?
            ;
        ", [$data['uri'], $lasted, json_encode($data['data'])]);
    }
}