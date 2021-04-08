<?php

namespace Phapi\Application;

use Phalcon\DI;
use Phalcon\Di\Injectable;
use Phalcon\Http\Request;
use Phalcon\Http\Response;
use Phalcon\Version;
use Phapi\Exceptions\ApiException;
use Phapi\Utility\ApplicationUtil;

class Rest extends Injectable
{

    const CONTENT_TYPE = "application/json";
    const ENCODING = "UTF-8";
    protected Response $response;
    protected Request $request;

    public function __construct()
    {
        $this->response = new Response();
        $this->request = new Request();
    }

    /**
     * Sends to the response back to client.
     *
     * @var ApiResponse|ApiError $response
     */
    public function sendResponse($response)
    {
        $di = DI::getDefault();

        $this->response->setContentType(self::CONTENT_TYPE, self::ENCODING);
        $this->response->setHeader('X-PHP-Version', PHP_VERSION);
        $this->response->setHeader('X-Phalcon-Version', Version::get());

        $content = [];

        if ($response instanceof ApiResponse) {
            $this->response->setStatusCode(200, 'OK');
            $content['data'] = $response->data;
        } else if ($response instanceof ApiError) {
            $this->response->setStatusCode($response->meta['status_code']);
            $content['errors'] = $response->errors;
        } else {
            throw new ApiException('return type from controller handler method must be ApiResponse|ApiError');
        }

        $content['meta'] = $response->meta;

        if ($di->get('registry')->get('profilerEnabled')) {
            $content['profiler'] = ApplicationUtil::getProfilerData();
        }

        $this->response->setContent(json_encode($content));
        if(!$this->response->isSent()) {
            $this->response->send();
        }
        exit;
    }
}