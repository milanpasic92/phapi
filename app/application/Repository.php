<?php

namespace Phapi\Application;

use MicheleAngioni\PhalconRepositories\AbstractRepository;

class Repository extends AbstractRepository
{
    protected $model;
    protected $di;
    protected $user;
    protected $modelsManager;

    public function __construct($model)
    {
        $di = \Phalcon\DI::getDefault();

        $this->model = $model;
        $this->di = $di;
    }
}