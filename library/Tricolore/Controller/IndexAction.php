<?php
namespace Tricolore\Controller;

use Tricolore\Services\ServiceLocator;

class IndexAction extends ServiceLocator
{
    /**
     * Send index page
     * 
     * @return void
     */
    public function index()
    {
        return $this->get('view')->getEnv()->loadTemplate('Actions/IndexAction.html.twig')->display([]);
    }
}
