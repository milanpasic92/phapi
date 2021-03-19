<?php

namespace Phapi\Application;

class ErrorHandler{

    public static function run(){
        set_error_handler(function($errno, $errstr, $errfile, $errline) {
            throw new \Phapi\Exceptions\BaseException($errstr, 0, $errno, $errfile, $errline);
        }, E_ALL);
    }

}
