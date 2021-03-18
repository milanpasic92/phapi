<?php

namespace Phapi\Exceptions;

use Phalcon\Exception;
use Throwable;

class BaseException extends Exception{

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

    public function handle(){
        $di = \Phalcon\DI::getDefault();

        $data = [
            'success' => false,
            'data' => [
                'message' => $this->getMessage(),
                'code' => $this->getCode(),
                'file' => $this->file,
                'severity' => $this->severity,
                'line' => $this->line
                ],
            'message' => PROJECT_NAME . 'APIError'
        ];

        $di->get('logger')->log($data);
        $di->get('rest')->sendResponse($data);
    }

}