<?php

namespace Phapi\Exceptions;

use Phalcon\DI;
use Phapi\Application\ApiError;

class NotFoundException extends BaseException
{

    public function handle()
    {
        $di = DI::getDefault();

        $data = [
            'errors' => [
                [
                    'err_key' => 'route_not_fond',
                    'message' => 'Route not found',
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