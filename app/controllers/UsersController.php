<?php

namespace Phapi\Controllers;

use Phapi\Application\ApiResponse;
use Phapi\Models\AdminUser;

class UsersController extends BaseController
{
    /**
     * Returns user list
     *
     * @return ApiResponse
     */
    public function indexAction()
    {
        $limit = $this->request->getQuery('limit', 'int', 10);
        $page = $this->request->getQuery('page', 'int', 1);

        $paginator = new \Phalcon\Paginator\Adapter\Model([
            "model"      => AdminUser::class,
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
    public function addAction()
    {
        return new ApiResponse('ce bude');
    }

    /**
     * Updating existing user
     *
     * @param int $userId
     * @return ApiResponse
     */
    public function updateAction(int $userId)
    {
        return new ApiResponse('ce bude');
    }

    /**
     * Delete an existing user
     *
     * @param int $userId
     * @return ApiResponse
     */
    public function deleteAction(int $userId)
    {
        return new ApiResponse('ce bude');
    }
}