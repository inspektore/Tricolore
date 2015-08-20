<?php
namespace Tricolore\Controller\Frontend;

use Tricolore\Controller\ControllerAbstract;

class Hello extends ControllerAbstract
{
    /**
     * @Role ROLE_ADMIN
     * @NoPermissionMessage Test admin role
     */
    public function sayHello($name)
    {
        $render = [
            'name' => $name
        ];

        return $this->get('view')->display('Actions/Frontend', 'Hello', $render);
    }
}
