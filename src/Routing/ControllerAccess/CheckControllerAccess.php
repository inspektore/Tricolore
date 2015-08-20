<?php
namespace Tricolore\Routing\ControllerAccess;

use Tricolore\Services\ServiceLocator;
use Tricolore\Exception\NoPermissionException;
use Tricolore\Exception\RuntimeException;
use phpDocumentor\Reflection\DocBlock;
use Symfony\Component\Security\Core\Util\StringUtils;

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

        if (!count($phpdoc->getTagsByName('Access')) && !count($phpdoc->getTagsByName('Role'))) {
            throw new NoPermissionException($this->errorMessage($phpdoc));
        }

        if (count($phpdoc->getTagsByName('Role'))) {
            $role_access = $phpdoc->getTagsByName('Role')[0]->getDescription();

            if ($this->get('acl.manager')->roleExists($role_access) === false) {
                throw new RuntimeException(sprintf('Role "%s" do not exists', $role_access));
            }

            if (StringUtils::equals($this->get('member')->getRole(), $role_access) === false) {
                throw new NoPermissionException($this->errorMessage($phpdoc));
            }
        }

        if (count($phpdoc->getTagsByName('Access'))) {
            $access = $phpdoc->getTagsByName('Access')[0]->getDescription();

            if ($this->get('acl.manager')->isGranded($access) === false) {
                throw new NoPermissionException($this->errorMessage($phpdoc));
            }
        }
    }

    /**
     * Try get error message
     * 
     * @param phpDocumentor\Reflection\DocBlock $phpdoc 
     * @return string
     */
    private function errorMessage(DocBlock $phpdoc)
    {
        if (count($phpdoc->getTagsByName('NoPermissionMessage'))) {
            return $this->get('translator')->trans($phpdoc->getTagsByName('NoPermissionMessage')[0]->getDescription());
        }
        
        return $this->get('translator')->trans('You have no permission to do this');
    }
}
