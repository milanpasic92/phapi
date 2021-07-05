<?php

namespace Phapi\Exceptions;

use Phalcon\DI;
use Phalcon\Exception;
use Phapi\Application\ApiError;

class BaseException extends Exception
{

    protected $file;
    protected $line;
    protected $severity;

    public function __construct($message = "", $code = 0, $severity = 0, $errFile = '', $errLine = 0)
    {
        $this->file = $errFile;
        $this->line = $errLine;
        $this->severity = $severity;
        parent::__construct($message, $code);
    }

    public function handle()
    {
        $di = DI::getDefault();

        $data = [
            'errors' => [
                [
                    'err_key' => 'internal_error',
                    'message' => 'Internal API error',
                    'details' => [
                        'message' => $this->getMessage(),
                        'trace' => $this->getTrace(),
                        'code' => $this->getCode(),
                        'file' => $this->file,
                        'severity' => $this->severity,
                        'line' => $this->line
                    ],
                ]
            ],
            'meta' => [
                'status_code' => 418
            ]
        ];

        $di->get('logger')->log($data);

        $response = new ApiError($data['errors'], $data['meta']);
        $di->get('rest')->sendResponse($response);
    }
}