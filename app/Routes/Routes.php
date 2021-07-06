<?php

namespace Phapi\Routes;

use Phalcon\DI;
use Phalcon\Mvc\Micro\Collection;
use Phapi\Exceptions\NotFoundException;

class Routes
{
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
        $publicRoutes = $di->get('acl')->publicApiRoutes;
        $di->get('registry')->set('publicApiRoutes', $publicRoutes);
    }

    private function initIdentity()
    {
        $identityCollection = new Collection();

        $identityCollection->setHandler('\Phapi\Controllers\IdentityController', true);
        $identityCollection->setPrefix('/identity');

        $identityCollection->post('/login', 'loginAction');
        $identityCollection->post('/password-reset', 'passwordResetAction');

        $this->app->mount($identityCollection);
    }

    private function initUsers()
    {
        $usersCollection = new Collection();

        $usersCollection->setHandler('\Phapi\Controllers\UsersController', true);
        $usersCollection->setPrefix('/users');

        $usersCollection->get('/', 'indexAction');
        $usersCollection->post('/', 'addAction');
        $usersCollection->put('/{userId:[1-9][0-9]*}', 'updateAction');
        $usersCollection->delete('/{userId:[1-9][0-9]*}', 'deleteAction');

        $this->app->mount($usersCollection);
    }
}
