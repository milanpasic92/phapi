<?php

namespace Phapi\Application;

use Phalcon\Events\Event;
use Phalcon\Mvc\Micro;

class CorsMiddleware
{
    public function beforeHandleRoute(Event $event, Micro $application)
    {
        if ($application->di->get('rest')->request->getHeader('X-Origin')) {
            $origin = $application->di->get('rest')->request->getHeader('X-Origin');
        } else {
            $origin = '*';
        }

        $application->di->get('rest')->response->setHeader('Access-Control-Allow-Origin', $origin)
            ->setHeader('Access-Control-Allow-Methods', 'GET,PUT,POST,DELETE,OPTIONS')
            ->setHeader('Access-Control-Allow-Headers', 'Content-Type, Origin, Access-Control-Allow-Origin, *')
            ->setHeader('Access-Control-Allow-Credentials', 'true');

        $application->di->get('rest')->response->sendHeaders();
    }
}