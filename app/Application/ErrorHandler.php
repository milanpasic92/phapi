<?php

namespace Phapi\Application;

use Phapi\Exceptions\ApiException;
use Phapi\Exceptions\BaseException;

class ErrorHandler
{

    public static function run()
    {
        set_error_handler(function ($errno, $errstr, $errfile, $errline) {
            $exception = new BaseException($errstr, 0, $errno, $errfile, $errline);
            $exception->handle();
        }, E_ALL);

        register_shutdown_function(function (){
            $error = error_get_last();

            if($error !== NULL) {
                $errno = $error["type"];
                $errfile = $error["file"];
                $errline = $error["line"];
                $errstr = $error["message"];

                $exception = new BaseException($errstr, 0, $errno, $errfile, $errline);
                $exception->handle();
            }
        });
    }

}
