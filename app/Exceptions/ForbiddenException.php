<?php

namespace Phapi\Exceptions;

use Phalcon\DI;
use Phapi\Application\ApiError;

class ForbiddenException extends BaseException
{

    public function handle()
    {
        $di = DI::getDefault();

        $data = [
            'errors' => [
                [
                    'err_key' => 'forbidden',
                    'message' => 'Access to content forbidden due to role or some other restriction.',
                    'details' => $this->getMessage(),
                ]
            ],
            'meta' => [
                'status_code' => 403
            ]
        ];

        $response = new ApiError($data['errors'], $data['meta']);
        $di->get('rest')->sendResponse($response);
    }

}