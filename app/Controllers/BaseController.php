<?php

namespace Phapi\Controllers;

use Phalcon\DI\Injectable;

abstract class BaseController extends Injectable
{
    public function __construct()
    {
        $this->request = $this->rest->request;
        $this->response = $this->rest->response;
    }
}