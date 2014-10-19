<?php
namespace Tricolore\Controller;

use Tricolore\Services\ServiceLocator;

class HelloAction extends ServiceLocator
{
    /**
     * @Route('/hello/{name}', name="hello")
     */
    public function sayHello(\stdClass $route)
    {
        $render = [
            'name' => $route->name
        ];

        return $this->get('view')->display('Actions', 'HelloAction', $render);
    }
}
