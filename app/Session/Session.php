<?php
namespace Tricolore\Session;

use Tricolore\Application;
use Tricolore\Config\Config;
use Tricolore\Services\ServiceLocator;
use Symfony\Component\Form\Extension\Csrf\CsrfProvider\SessionCsrfProvider;
use Symfony\Component\HttpFoundation\Session\Session as SessionProvider;
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
    public static function begin()
    {
        $pdo = self::getInstance()->get('datasource')->getPdo();

        self::$pdo_handler = new PdoSessionHandler($pdo);

        $storage = new NativeSessionStorage([], self::$pdo_handler);

        self::$session = new SessionProvider($storage);

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
     * @return Symfony\Component\Form\Extension\Csrf\CsrfProvider\SessionCsrfProvider
     */
    public static function csrfProvider()
    {
        return new SessionCsrfProvider(self::getSession(), 'some_secret_token');
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
    public static function handler()
    {
        return self::$pdo_handler;
    }

    /**
     * Session accessor
     * 
     * @codeCoverageIgnore
     * @return Symfony\Component\HttpFoundation\Session\Session
     */
    public static function getSession()
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
    public static function getSessionId()
    {
        return self::getSession()->getId();
    }

    /**
     * Session expired
     * 
     * @codeCoverageIgnore
     * @return bool
     */
    public static function expired()
    {
        return self::$pdo_handler->isSessionExpired();
    }
}
