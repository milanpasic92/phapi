<?php

namespace Phapi\Exceptions;

use Phalcon\DI;
use Phapi\Application\ApiError;

class ContentTypeException extends BaseException
{

    public function handle()
    {
        $di = DI::getDefault();

        $data = [
            'errors' => [
                [
                    'err_key' => 'invalid_content_type',
                    'message' => 'Payload format is in an unsupported format. Content-Type `application/json` supported only.',
                    'details' => ''
                ]
            ],
            'meta' => [
                'status_code' => 415
            ]
        ];

        $response = new ApiError($data['errors'], $data['meta']);
        $di->get('rest')->sendResponse($response);
    }

}