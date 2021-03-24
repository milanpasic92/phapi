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
                    'message' => 'Route not found'
                ]
            ],
            'meta' => [
                'status_code' => 404
            ]
        ];

        $di->get('logger')->log($data);

        $response = new ApiError($data['errors'], $data['meta']);
        $di->get('rest')->sendResponse($response);
    }

}