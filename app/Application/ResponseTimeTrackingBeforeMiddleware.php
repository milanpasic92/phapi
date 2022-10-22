<?php

namespace Phapi\Application;

use Phalcon\DI;

class ResponseTimeTrackingBeforeMiddleware
{
    public function beforeExecuteRoute()
    {
        $di = DI::getDefault();
        $di->get('registry')->set('phapi.responseTimeInfo', [
            'startedAt' => hrtime(true),
            'uri' => $di->get('rest')->request->getURI()
        ]);
    }
}