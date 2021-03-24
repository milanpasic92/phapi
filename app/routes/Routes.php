<?php

namespace Phapi\Routes;

class Routes{

    protected $app;
    public array $publicRoutes = ['/identity/login', '/identity/password-reset'];

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function init(){
        $this->initIdentity();
        $this->initUsers();

        $this->app->notFound(
            function () {
                throw new \Phapi\Exceptions\NotFoundException();
            }
        );

        $di = \Phalcon\DI::getDefault();
        $di->get('registry')->set('publicApiRoutes', $this->publicRoutes);
    }

    private function initIdentity(){
        $identityCollection = new \Phalcon\Mvc\Micro\Collection();

        $identityCollection->setHandler('\Phapi\Controllers\IdentityController', true);
        $identityCollection->setPrefix('/identity');

        $identityCollection->post('/login', 'loginAction');
        $identityCollection->get('/password-reset', 'passwordResetAction');

        $this->app->mount($identityCollection);
    }

    private function initUsers(){
        $usersCollection = new \Phalcon\Mvc\Micro\Collection();

        $usersCollection->setHandler('\Phapi\Controllers\UsersController', true);
        $usersCollection->setPrefix('/users');

        $usersCollection->post('/add', 'addAction');
        $usersCollection->get('/', 'indexAction');
        $usersCollection->put('/{userId:[1-9][0-9]*}', 'updateAction');
        $usersCollection->delete('/{userId:[1-9][0-9]*}', 'deleteAction');

        $this->app->mount($usersCollection);
    }
}
