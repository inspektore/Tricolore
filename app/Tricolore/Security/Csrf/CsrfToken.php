<?php
namespace Tricolore\Security\Csrf;

use Tricolore\Session\Session;
use Symfony\Component\HttpFoundation\Request;

class CsrfToken
{
    /**
     * Is CSRF token valid
     * 
     * @codeCoverageIgnore
     * @param string $intention
     * @param string $token_field
     * @return bool
     */
    public static function isValid($intention, $token_field = '_token')
    {
        return Session::csrfProvider()->isCsrfTokenValid($intention, Request::createFromGlobals()->get($token_field));
    }
}
