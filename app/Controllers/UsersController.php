<?php

namespace Phapi\Controllers;

use Phalcon\Paginator\Adapter\Model;
use Phapi\Application\ApiResponse;
use Phapi\Models\AdminUser;
use Phapi\Repository\UsersRepository;

class UsersController extends BaseController
{
    /**
     * Returns user list
     *
     * @return ApiResponse
     */
    public function indexAction() : ApiResponse
    {

        $repo = $this->di->get('repo', ['UsersRepository', new AdminUser()]);

        $users[] = $repo->getById(2);
        $users[] = $repo->getById(2);
        $users[] = $repo->getById(2);
        $users[] = $repo->getById(2);

        $limit = $this->request->getQuery('limit', 'int', 10);
        $page = $this->request->getQuery('page', 'int', 1);

        $paginator = new Model([
            "model" => AdminUser::class,
            "parameters" => [
                "active = :activeFlag:",
                "bind" => [
                    "activeFlag" => 1
                ],
                "order" => "id"
            ],
            "limit" => $limit,
            "page" => $page
        ]);
        $items = $paginator->paginate();

        return new ApiResponse($items->getItems(), [
            'page' => $page,
            'limit' => $limit,
            'total_items' => $items->getTotalItems()
        ]);
    }

    /**
     * Adding user
     *
     * @return ApiResponse
     */
    public function addAction() : ApiResponse
    {
        $usersRepo = new UsersRepository(new AdminUser());
        return new ApiResponse($usersRepo->test());
    }

    /**
     * Updating existing user
     *
     * @param int $userId
     * @return ApiResponse
     */
    public function updateAction(int $userId) : ApiResponse
    {
        return new ApiResponse('ce bude');
    }

    /**
     * Delete an existing user
     *
     * @param int $userId
     * @return ApiResponse
     */
    public function deleteAction(int $userId) : ApiResponse
    {
        return new ApiResponse('ce bude');
    }
}