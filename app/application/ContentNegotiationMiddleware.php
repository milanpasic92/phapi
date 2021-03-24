<?php

namespace Phapi\Application;

class ContentNegotiationMiddleware
{
    public function beforeExecuteRoute()
    {
        $di = \Phalcon\DI::getDefault();
        $request = $di->get('rest')->request;
        $contentType = $request->getHeader('Content-Type');
        if (strpos($contentType, 'application/json') !== false) {
            $rawBody = $request->getJsonRawBody(true);
            if ($request->isPost()) {
                if(is_array($rawBody)) {
                    foreach ($rawBody as $key => $value) {
                        $_POST[$key] = $value;
                    }
                }
            }
        }
    }
}