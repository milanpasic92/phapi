<?php

namespace Phapi\Controllers;

use Phalcon\Paginator\Adapter\Model;
use Phapi\Application\ApiResponse;
use Phapi\Application\Response;
use Phapi\Models\AdminUser;
use Phapi\Repository\UsersRepository;

class UsersController extends BaseController
{
    /**
     * Returns user list
     *
     * @return Response
     */
    public function indexAction()
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
     * @return Response
     */
    public function addAction()
    {
        $usersRepo = new UsersRepository(new AdminUser());
        return new ApiResponse($usersRepo->test());
    }

    /**
     * Updating existing user
     *
     * @param int $userId
     * @return Response
     */
    public function updateAction(int $userId)
    {
        return new ApiResponse('ce bude');
    }

    /**
     * Delete an existing user
     *
     * @param int $userId
     * @return Response
     */
    public function deleteAction(int $userId)
    {
        return new ApiResponse('ce bude');
    }
}