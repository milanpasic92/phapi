<?php

namespace Phapi\Application;

use Phalcon\Di\Injectable;
use Phapi\Exceptions\ApiException;
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

    /**
     * Sends to the response back to client.
     *
     * @var ApiResponse|ApiError $response
    */
    public function sendResponse($response){
        $di = \Phalcon\DI::getDefault();

        $this->response->setContentType(self::CONTENT_TYPE, self::ENCODING);
        $this->response->setHeader('X-PHP-Version', PHP_VERSION);
        $this->response->setHeader('X-Phalcon-Version', \Phalcon\Version::get());

        $content = [];

        if($response instanceof ApiResponse){
            $this->response->setStatusCode(200, 'OK');
            $content['data'] = $response->data;
        }
        else if ($response instanceof ApiError){
            $this->response->setStatusCode($response->meta['status_code']);
            $content['errors'] = $response->errors;
        }
        else{
            throw new ApiException('return type from controller handler method must be ApiResponse|ApiError');
        }

        $content['meta'] = $response->meta;

        if($di->get('registry')->get('profilerEnabled')){
            $content['profiler'] = ApplicationUtil::getProfilerData();
        }

        $this->response->setContent(json_encode($content));
        $this->response->send();
    }
}