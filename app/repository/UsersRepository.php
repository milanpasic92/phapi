<?php

namespace Phapi\Repository;

use Phapi\Application\Repository;
use Phapi\Models\AdminUser;

class UsersRepository extends Repository
{
    protected $model;

    public function __construct(AdminUser $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    public function test(){
        return 'data';
    }
}