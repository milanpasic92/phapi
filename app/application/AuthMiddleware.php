<?php

namespace Phapi\Application;

class AuthMiddleware
{
    public function beforeExecuteRoute()
    {
        $di = \Phalcon\DI::getDefault();

        if(false){
            return false;
        }

        return true;
    }
}