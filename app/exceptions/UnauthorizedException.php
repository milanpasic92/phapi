<?php

namespace Phapi\Exceptions;

use Phalcon\DI;
use Phapi\Application\ApiError;

class UnauthorizedException extends BaseException
{

    public function handle()
    {
        $di = DI::getDefault();

        $data = [
            'errors' => [
                [
                    'err_key' => 'unauthorized',
                    'message' => 'JWT token expired or invalid',
                    'details' => ''
                ]
            ],
            'meta' => [
                'status_code' => 401
            ]
        ];

        $response = new ApiError($data['errors'], $data['meta']);
        $di->get('rest')->sendResponse($response);
    }

}