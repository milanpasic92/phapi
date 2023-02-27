<?php

namespace Phapi\Application;

use Phalcon\Events\Event;
use Phalcon\Mvc\Micro;

class CorsMiddleware
{
    /**
     * Adds CORS headers to all (GET, POST, PUT...) methods except for OPTIONS (preflight) requests.
     * @see handlePreflight() method for headers for OPTIONS requests.
    */
    public function beforeHandleRoute(Event $event, Micro $application)
    {
        $request = $application->di->get('rest')->request;

        if ($request->getHeader('Origin')) {
            $origin = $request->getHeader('Origin');
        }
        else if($request->getHeader('x-origin')){
            $origin = $request->getHeader('x-origin');
        }
        else {
            $origin = '*';
        }

        $application->di->get('rest')->response->setHeader('Access-Control-Allow-Origin', $origin)
            ->setHeader('Access-Control-Allow-Methods', 'GET,PUT,POST,DELETE,OPTIONS')
            ->setHeader('Access-Control-Allow-Headers',
                'Content-Type, Origin, x-origin, authorization, Cache-Control, x-cypress-is-xhr-or-fetch, *')
            ->setHeader('Access-Control-Allow-Credentials', 'true');

        $application->di->get('rest')->response->sendHeaders();
    }
}