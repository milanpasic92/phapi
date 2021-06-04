<?php

namespace Phapi\Application;

use Phalcon\DI;
use Phapi\Exceptions\ContentTypeException;

class ContentNegotiationMiddleware
{
    public function beforeExecuteRoute()
    {
        $di = DI::getDefault();
        $request = $di->get('rest')->request;
        $contentType = $request->getHeader('Content-Type');
        if (!empty($contentType)) {
            if (strpos($contentType, 'application/json') !== false) {
                $rawBody = $request->getJsonRawBody(true);
                if (is_array($rawBody)) {
                    foreach ($rawBody as $key => $value) {
                        $_POST[$key] = $value;
                    }
                }
            }
            else if(strpos($contentType, 'multipart/form-data') !== false) {
                // Post is post xD
                // $_POST = $_POST;
            }
            else {
                throw new ContentTypeException();
            }
        }
    }
}