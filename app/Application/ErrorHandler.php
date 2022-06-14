<?php

namespace Phapi\Application;

use Phapi\Exceptions\ApiException;
use Phapi\Exceptions\BaseException;

class ErrorHandler
{
    const SKIP = [
        'bytes exceeds the limit of' // POST Content-Type warnings for posting files bigger than php_ini `upload_max_filesize`
    ];

    public static function run()
    {
        set_error_handler(function ($errno, $errstr, $errfile, $errline) {
            $exception = new BaseException($errstr, 0, $errno, $errfile, $errline);
            $exception->handle();
        }, E_WARNING);

        register_shutdown_function(function (){
            $error = error_get_last();

            if($error !== NULL && $error['type'] <= 2) // catch only warnings or lower
            {
                $report = true;

                foreach (self::SKIP as $message){
                    if(strpos($error['message'], $message) !== false){
                        $report = false;
                        break;
                    }
                }

                if($report) {
                    $errno = $error["type"];
                    $errfile = $error["file"];
                    $errline = $error["line"];
                    $errstr = $error["message"];

                    $exception = new BaseException($errstr, 0, $errno, $errfile, $errline);
                    $exception->handle();
                }
            }
        });
    }

}
