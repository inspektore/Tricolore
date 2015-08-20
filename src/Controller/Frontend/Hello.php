<?php
namespace Tricolore\Controller\Frontend;

use Tricolore\Controller\ControllerAbstract;

class Hello extends ControllerAbstract
{
    /**
     * @Access admincp_access
     * @NoPermissionMessage You have no permission to see this page
     */
    public function sayHello($name)
    {
        $render = [
            'name' => $name
        ];

        return $this->get('view')->display('Actions/Frontend', 'Hello', $render);
    }
}
