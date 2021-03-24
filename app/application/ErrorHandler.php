<?php

namespace Phapi\Application;

use Phapi\Exceptions\BaseException;

class ErrorHandler
{

    public static function run()
    {
        set_error_handler(function ($errno, $errstr, $errfile, $errline) {
            throw new BaseException($errstr, 0, $errno, $errfile, $errline);
        }, E_ALL);
    }

}
