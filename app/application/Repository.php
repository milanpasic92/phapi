<?php

namespace Phapi\Application;

use MicheleAngioni\PhalconRepositories\AbstractRepository;
use Phalcon\Mvc\Model\Manager;

class Repository extends AbstractRepository
{
    protected $model;
    protected $di;
    protected Manager $modelsManager;

    protected array $memoryCache;

    public function __construct($model)
    {
        $di = \Phalcon\DI::getDefault();

        $this->model = $model;
        $this->di = $di;
        $this->modelsManager = new Manager();
        $this->modelsManager->setDI($this->di);

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