<?php

namespace Phapi\Exceptions;

use Phalcon\DI;
use Phapi\Application\ApiError;

class NotFoundException extends BaseException
{
    protected $err_key;
    protected $message;

    public function __construct($err_key = "route_not_fond", $message = "Route not found")
    {
        $this->err_key = $err_key;
        $this->message = $message;

        parent::__construct($message);
    }

    public function handle()
    {
        $di = DI::getDefault();

        $data = [
            'errors' => [
                [
                    'err_key' => $this->err_key,
                    'message' => $this->message,
                    'details' => ''
                ]
            ],
            'meta' => [
                'status_code' => 404
            ]
        ];

        $response = new ApiError($data['errors'], $data['meta']);
        $di->get('rest')->sendResponse($response);
    }

}