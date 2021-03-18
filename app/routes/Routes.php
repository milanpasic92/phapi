<?php

namespace Phapi\Routes;

class Routes{

    protected $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    function init(){
        $usersCollection = new \Phalcon\Mvc\Micro\Collection();

        $usersCollection->setHandler('\Phapi\Controllers\UsersController', true);
        $usersCollection->setPrefix('/users');

        $usersCollection->post('/add', 'addAction');
        $usersCollection->get('/', 'indexAction');
        $usersCollection->put('/{userId:[1-9][0-9]*}', 'updateAction');
        $usersCollection->delete('/{userId:[1-9][0-9]*}', 'deleteAction');

        $this->app->mount($usersCollection);

        $this->app->notFound(
            function () {
                throw new \Phapi\Exceptions\NotFoundException();
            }
        );
    }
}
