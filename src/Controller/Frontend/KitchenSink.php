<?php
namespace Tricolore\Controller\Frontend;

use Tricolore\Controller\ControllerAbstract;

class KitchenSink extends ControllerAbstract
{
    /**
     * @Access can_see_index
     */
    public function woah()
    {
        return $this->get('view')->display('Actions/Frontend', 'KitchenSink');
    }
}
