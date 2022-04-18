<?php

namespace Phapi\Exceptions;

use Phalcon\DI;
use Phalcon\Exception;
use Phapi\Application\ApiError;

class ApiException extends Exception
{
    const SILENT_ERRORS = [
        'service_not_found'
    ];

    public function handle()
    {
        $di = DI::getDefault();

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

        if(!in_array($this->getMessage(), self::SILENT_ERRORS)) {
            $di->get('logger')->log($data);
        }

        $response = new ApiError($data['errors'], $data['meta']);
        $di->get('rest')->sendResponse($response);
    }
}