<?php

namespace Phapi\Controllers;

class UsersController extends BaseController
{
    /**
     * Adding user
     */
    public function addAction()
    {

    }

    /**
     * Returns user list
     *
     * @return array
     */
    public function indexAction()
    {
        return [
            "success" => true,
            "data" => 'hello world.'
        ];
    }

    /**
     * Updating existing user
     *
     * @param int $userId
     */
    public function updateAction($userId)
    {

    }

    /**
     * Delete an existing user
     *
     * @param int $userId
     */
    public function deleteAction($userId)
    {

    }
}