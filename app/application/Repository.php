<?php

namespace Phapi\Application;

use MicheleAngioni\PhalconRepositories\AbstractRepository;

class Repository extends AbstractRepository
{
    protected $model;
    protected $di;
    protected $user;
    protected $modelsManager;

    protected array $memoryCache;

    public function __construct($model)
    {
        $di = \Phalcon\DI::getDefault();

        $this->model = $model;
        $this->di = $di;

        $this->memoryCache = [];
    }

    public function getById(int $id)
    {
        if(!isset($this->memoryCache[$id])){
            $this->memoryCache[$id] = $this->model::findFirst($id);
        }
        return $this->memoryCache[$id];
    }
}