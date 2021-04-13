<?php

namespace Phapi\Application;

use Phalcon\DI;
use Phalcon\Events\Event;
use Phalcon\Mvc\Micro;

class CorsMiddleware
{
    public function beforeHandleRoute(Event $event, Micro $application)
    {
        if ($application->di->get('rest')->request->getHeader('ORIGIN')) {
            $origin = $application->di->get('rest')->request->getHeader('ORIGIN');
        } else {
            $origin = '*';
        }

        $application->di->get('rest')->response->setHeader('Access-Control-Allow-Origin', $origin)
            ->setHeader('Access-Control-Allow-Methods', 'GET,PUT,POST,DELETE,OPTIONS')
            ->setHeader('Access-Control-Allow-Headers', 'Access-Control-Allow-Origin, *')
            ->setHeader('Access-Control-Allow-Credentials', 'true');
    }
}