<?php
namespace Tricolore\Controller\Frontend;

use Tricolore\Controller\ControllerAbstract;

class Index extends ControllerAbstract
{
    /**
     * @Access can_see_index
     * @Route('/', name="home")
     */
    public function index()
    {
        return $this->get('view')->display('Actions/Frontend', 'Index');
    }
}
