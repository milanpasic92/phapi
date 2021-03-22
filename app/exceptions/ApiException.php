<?php

namespace Phapi\Exceptions;

use Phalcon\Exception;

class ApiException extends Exception{

    public function handle(){
        $di = \Phalcon\DI::getDefault();

        $data = [
            'errors' => [
                [
                    'err_key' => 'internal_error',
                    'message' => 'Internal API error'
                ]
            ],
            'meta' => [
                'status_code' => 400
            ]
        ];

        $di->get('logger')->log($data);
        $di->get('rest')->sendResponse($data);
    }
}