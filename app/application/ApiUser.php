<?php

namespace Phapi\Application;

use Monolog\Handler\MissingExtensionException;
use Phalcon\Di\Injectable;

class ApiUser extends Injectable {

    public array $data;
    public string $token;

    public function __construct(array $data, string $token)
    {
        $this->data = $data;
        $this->token = $token;
    }
}