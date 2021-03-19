<?php

namespace Phapi\Application;

use Phapi\Exceptions\ApiException;
use Phalcon\Di\Injectable;

class Rest extends Injectable {

    protected \Phalcon\Http\Response $response;
    protected \Phalcon\Http\Request $request;

    const CONTENT_TYPE = "application/json";
    const ENCODING = "UTF-8";

    public function __construct()
    {
        $this->response = new \Phalcon\Http\Response();
        $this->request = new \Phalcon\Http\Request();
    }

    public function sendResponse($content){
        $this->response->setContentType(self::CONTENT_TYPE, self::ENCODING);
        $this->response->setHeader('X-PHP-Version', PHP_VERSION);
        $this->response->setHeader('X-Phalcon-Version', \Phalcon\Version::get());

        if (is_array($content))
        {
            if($content['success']){
                $this->response->setStatusCode(200, 'OK');
            }
            else{
                $this->response->setStatusCode(400, 'Bad Request');
            }

            $this->response->setContent(json_encode($content));
        }
        else {
            throw new ApiException('Internal API error.', 400);
        }

        $this->response->send();
    }
}