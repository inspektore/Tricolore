<?php
namespace Tricolore\Security\Csrf;

use Tricolore\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfToken as Token;

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
        $token = new Token($intention, Request::createFromGlobals()->request->get($token_field));

        return Session::getInstance()->csrfProvider()->isTokenValid($token);
    }
}
