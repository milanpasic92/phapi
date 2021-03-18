<?php

namespace Phapi\Application;

use Phapi\Exceptions\BaseException;
use Phalcon\Di\Injectable;
use Phalcon\Events\Event;

class Rest extends Injectable {

    /**
     * Creates the profiler and starts the logging
     */
    public function __construct()
    {
        /*$this->profiler = new \Phalcon\Db\Profiler();*/
    }

    public function sendResponse($content){
        $contentType = 'application/json';
        $encoding = 'UTF-8';
        $this->response->setContentType($contentType, $encoding);

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
            throw new BaseException('Internal API error.');
        }

        $this->response->send();
    }
}