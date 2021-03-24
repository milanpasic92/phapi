<?php

namespace Phapi\Routes;

use Phalcon\DI;
use Phalcon\Mvc\Micro\Collection;
use Phapi\Exceptions\NotFoundException;

class Routes
{

    public array $publicRoutes = ['/identity/login', '/identity/password-reset'];
    protected $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function init()
    {
        $this->initIdentity();
        $this->initUsers();

        $this->app->notFound(
            function () {
                throw new NotFoundException();
            }
        );

        $di = DI::getDefault();
        $di->get('registry')->set('publicApiRoutes', $this->publicRoutes);
    }

    private function initIdentity()
    {
        $identityCollection = new Collection();

        $identityCollection->setHandler('\Phapi\Controllers\IdentityController', true);
        $identityCollection->setPrefix('/identity');

        $identityCollection->post('/login', 'loginAction');
        $identityCollection->get('/password-reset', 'passwordResetAction');

        $this->app->mount($identityCollection);
    }

    private function initUsers()
    {
        $usersCollection = new Collection();

        $usersCollection->setHandler('\Phapi\Controllers\UsersController', true);
        $usersCollection->setPrefix('/users');

        $usersCollection->post('/add', 'addAction');
        $usersCollection->get('/', 'indexAction');
        $usersCollection->put('/{userId:[1-9][0-9]*}', 'updateAction');
        $usersCollection->delete('/{userId:[1-9][0-9]*}', 'deleteAction');

        $this->app->mount($usersCollection);
    }
}
