<?php

namespace Phapi\Application;

use Phalcon\Di\Injectable;
use Phapi\Utility\ApplicationUtil;

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

    public function sendResponse(array $content){
        $di = \Phalcon\DI::getDefault();

        $this->response->setContentType(self::CONTENT_TYPE, self::ENCODING);
        $this->response->setHeader('X-PHP-Version', PHP_VERSION);
        $this->response->setHeader('X-Phalcon-Version', \Phalcon\Version::get());

        if(!isset($content['errors'])){
            $this->response->setStatusCode(200, 'OK');
        }
        else{
            $this->response->setStatusCode($content['meta']['status_code']);
        }

        if($di->get('registry')->get('profilerEnabled')){
            $content['profiler'] = ApplicationUtil::getProfilerData();
        }

        $this->response->setContent(json_encode($content));
        $this->response->send();
    }
}