<?php
namespace Tricolore\Routing\ControllerAccess;

use Tricolore\Services\ServiceLocator;
use Tricolore\Exception\NoPermissionException;
use phpDocumentor\Reflection\DocBlock;

class CheckControllerAccess extends ServiceLocator
{
    /**
     * Check access for controller
     * 
     * @param object $controller
     * @param string $method
     * @throws Tricolore\Exception\NoPermissionException
     * @return void
     */
    public function __construct($controller, $method)
    {
        $class = new \ReflectionClass(get_class($controller));
        $phpdoc = new DocBlock($class->getMethod($method)->getDocComment());

        if (!count($phpdoc->getTagsByName('Access'))) {
            throw new NoPermissionException($this->errorMessage($phpdoc));
        }

        $access = $phpdoc->getTagsByName('Access')[0]->getDescription();

        if ($this->get('acl.manager')->isGranded($access) === false) {
            throw new NoPermissionException($this->errorMessage($phpdoc));
        }
    }

    /**
     * Try get error message
     * 
     * @param phpDocumentor\Reflection\DocBlock $phpdoc 
     * @throws \Exception
     * @return void
     */
    private function errorMessage(DocBlock $phpdoc)
    {
        if (count($phpdoc->getTagsByName('NoPermissionMessage'))) {
            return $this->get('translator')->trans($phpdoc->getTagsByName('NoPermissionMessage')[0]->getDescription());
        }
        
        return $this->get('translator')->trans('You have no permission to do this');
    }
}
