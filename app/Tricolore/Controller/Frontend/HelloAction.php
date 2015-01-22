<?php
namespace Tricolore\Controller\Frontend;

use Tricolore\Services\ServiceLocator;

class HelloAction extends ServiceLocator
{
    /**
     * @Route('/hello/{name}', name="hello")
     */
    public function sayHello($name)
    {
        $this->get('datasource')->dropDatabase('cc');

        $render = [
            'name' => $name
        ];

        return $this->get('view')->display('Actions/Frontend', 'HelloAction', $render);
    }
}
