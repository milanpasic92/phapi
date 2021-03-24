<?php

namespace Phapi\Exceptions;

use Phalcon\Exception;
use Phapi\Application\ApiError;

class ApiException extends Exception{

    public function handle(){
        $di = \Phalcon\DI::getDefault();

        $data = [
            'errors' => [
                [
                    'err_key' => 'internal_error',
                    'message' => 'Internal API error',
                    'details' => $this->getMessage(),
                ]
            ],
            'meta' => [
                'status_code' => 400
            ]
        ];

        $di->get('logger')->log($data);

        $response = new ApiError($data['errors'], $data['meta']);
        $di->get('rest')->sendResponse($response);
    }
}