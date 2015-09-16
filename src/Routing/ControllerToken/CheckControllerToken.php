<?php
namespace Tricolore\Routing\ControllerToken;

use Tricolore\Services\ServiceLocator;
use Tricolore\Security\Csrf\CsrfToken;
use Tricolore\Exception\NoPermissionException;
use phpDocumentor\Reflection\DocBlock;

class CheckControllerToken extends ServiceLocator
{
    /**
     * Check CSRF token for controller
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

        if (count($phpdoc->getTagsByName('CsrfToken'))) {
            $token = $phpdoc->getTagsByName('CsrfToken')[0]->getDescription();
            $token_field = '_token';

            if (count($phpdoc->getTagsByName('CsrfTokenField'))) {
                $token_field = $phpdoc->getTagsByName('CsrfTokenField')[0]->getDescription();
            }

            if (CsrfToken::isValid($token, $token_field) === false) {
                throw new NoPermissionException($this->get('translator')->trans('The CSRF token is invalid. Please try to resubmit the form.'));
            }
        }
    }
}
