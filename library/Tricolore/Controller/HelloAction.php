<?php
namespace Tricolore\Controller;

use Tricolore\Services\ServiceLocator;

class HelloAction extends ServiceLocator
{
    /**
     * Example page with arguments
     * 
     * @param \stdObject $route
     * @return void
     */
    public function sayHello($route)
    {
        $render = [
            'name' => $route->name
        ];

        return $this->get('view')->display('Actions', 'HelloAction', $render);
    }
}
