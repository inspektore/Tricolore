<?php
namespace Tricolore\Controller\Frontend;

use Tricolore\Controller\ControllerAbstract;

class Index extends ControllerAbstract
{
    /**
     * @Access can_see_index
     * @NoPermissionMessage You have no permission to see this page
     */
    public function index()
    {
        return $this->get('view')->display('Actions/Frontend', 'Index');
    }
}
