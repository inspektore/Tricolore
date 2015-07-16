<?php
namespace Tricolore\Session;

use Tricolore\Foundation\Application;
use Tricolore\Config\Config;
use Tricolore\Services\ServiceLocator;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\HttpFoundation\Session\Session as SessionService;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\PdoSessionHandler;

class Session extends ServiceLocator
{
    /**
     * Session
     * 
     * @var Symfony\Component\HttpFoundation\Session\Session
     */
    private static $session;

    /**
     * PDO Handler
     * 
     * @var Symfony\Component\HttpFoundation\Session\Storage\Handler\PdoSessionHandler
     */
    private static $pdo_handler;

    /**
     * Begin session
     * 
     * @codeCoverageIgnore
     * @return void
     */
    public function begin()
    {
        $pdo = $this->get('datasource')->getPdo();
        self::$pdo_handler = new PdoSessionHandler($pdo);
        $storage = new NativeSessionStorage([], self::$pdo_handler);
        self::$session = new SessionService($storage);

        if (Application::getInstance()->getEnv() !== 'test') {
            self::$session->setName('session_id');
            self::$session->start();            
        }

        self::$pdo_handler->gc(1440);
    }

    /**
     * CSRF provider
     * 
     * @codeCoverageIgnore
     * @return Symfony\Component\Security\Csrf\CsrfTokenManager
     */
    public function csrfProvider()
    {
        return new CsrfTokenManager();
    }

    /**
     * Session instance
     *
     * @codeCoverageIgnore
     * @return Tricolore\Session\Session
     */
    public static function getInstance()
    {
        return new static();
    }

    /**
     * PDO handler accessor
     * 
     * @codeCoverageIgnore
     * @return Symfony\Component\HttpFoundation\Session\Storage\Handler\PdoSessionHandler
     */
    public function handler()
    {
        return self::$pdo_handler;
    }

    /**
     * Session accessor
     * 
     * @codeCoverageIgnore
     * @return Symfony\Component\HttpFoundation\Session\Session
     */
    public function getSession()
    {
        return self::$session;
    }

    /**
     * Get session id
     * 
     * @codeCoverageIgnore
     * @param string 
     * @throws \Exception
     * @return void
     */
    public function getSessionId()
    {
        return $this->getSession()->getId();
    }

    /**
     * Session expired
     * 
     * @codeCoverageIgnore
     * @return bool
     */
    public function expired()
    {
        return $this->pdo_handler->isSessionExpired();
    }
}
