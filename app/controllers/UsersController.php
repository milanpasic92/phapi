<?php

namespace Phapi\Controllers;

use Phapi\Models\AdminUser;

class UsersController extends BaseController
{
    /**
     * Adding user
     */
    public function addAction()
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

        return [
            'data' => $items->getItems(),
            'meta' => [
                'page' => $page,
                'limit' => $limit,
                'total_items' => $items->getTotalItems()
            ],
        ];
    }

    /**
     * Returns user list
     *
     * @return array
     */
    public function indexAction()
    {
        return [
            "data" => 'hello world.',
            "meta" => 'meta'
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