<?php

namespace Phapi\Application;


use Phalcon\Acl\Adapter\Memory;
use Phalcon\Acl\Role;

class ACL
{
    public Memory $acl;

    protected array $resources = [
        'guest'=>[
            'IdentityController'=>['loginAction','resetPasswordAction'],
        ],
        'restrictedUser'=>[
            'UsersController'=>['indexAction', 'addAction', 'updateAction', 'deleteAction'],
        ],
        'admin'=>[ // example
            'AccountsController' => ['upgradeAction','paymentHistoryAction','invoicesAction']
        ]
    ];

    public function __construct()
    {
        $this->acl = new Memory();
        $this->acl->setDefaultAction(\Phalcon\Acl\Enum::DENY);

        foreach ($this->resources as $role => $groups) {
            $this->acl->addRole(new Role($role));
        }

        foreach($this->resources as $arrResource){
            foreach($arrResource as $controller => $arrMethods){
                $this->acl->addComponent(new \Phalcon\Acl\Component($controller), $arrMethods);
            }
        }

        foreach ($this->acl->getRoles() as $objRole) {
            $roleName = $objRole->getName();

            foreach ($this->resources[$roleName] as $resource => $method) {
                $this->acl->allow($roleName,$resource,$method);
            }
        }
    }
}