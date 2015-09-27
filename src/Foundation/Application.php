<?php
namespace Tricolore\Foundation;

use Tricolore\Config\Config;
use Tricolore\Routing\Routing;
use Tricolore\Services\ServiceLocator;
use Tricolore\Exception\ErrorException;
use Tricolore\Exception\RuntimeException;
use Tricolore\Session\Session;
use Tricolore\View\ExceptionHandler\ExceptionHandler;
use Symfony\Component\Routing\Generator\UrlGenerator;

class Application extends ServiceLocator
{
    /**
     * Version of application
     *
     * @var string
     */
    private $version = '0.1.608';

    /**
     * Application options
     *
     * @var array
     */
    private static $options;

    /**
     * Routing object
     *
     * @var Tricolore\Routing\Routing
     */
    private static $routing;

    /**
     * Register application and services
     *
     * @param array $options
     * @throws Tricolore\Exception\RuntimeException
     * @return void
     */
    public static function register(array $options)
    {
        self::$options = $options;
        self::getInstance()->setupErrorReporting();

        setlocale(LC_ALL, Config::getParameter('base.locale'));
        date_default_timezone_set(Config::getParameter('base.timezone'));

        try {
            self::getInstance()->get('session.instance')->begin();

            if (self::getInstance()->inCli() === false) {
                self::$routing = new Routing();
                self::$routing->register();                
            }
        } catch (\Exception $exception) {
            $handler = new ExceptionHandler();

            $handler->handle($exception);
        }
    }

    /**
     * Setup error reporting
     *
     * @return void
     */
    private function setupErrorReporting()
    {
        if (self::getInstance()->getEnv() === 'prod') {
            error_reporting(0);
        } elseif (self::getInstance()->getEnv() === 'dev' 
            || self::getInstance()->getEnv() === 'test'
        ) {
            error_reporting(E_ALL);
        }

        set_error_handler(function ($errno, $errstr, $errfile, $errline, $errcontext) {
            if (error_reporting() === 0) {
                return false;
            }

            throw new ErrorException($errstr, $errno, $errfile, $errline);
        });
    }

    /**
     * Application instance
     *
     * @return Tricolore\Foundation\Application
     */
    public static function getInstance()
    {
        return new static();
    }

    /**
     * Build URL address
     *
     * @param string $route_name
     * @param array $arguments
     * @return string
     */
    public static function buildUrl($route_name = null, array $arguments = [])
    {
        if ($route_name == null) {
            return Config::getParameter('base.full_url');
        }

        if (self::$routing->getRouteCollection()->all()[$route_name] === null) {
            return Config::getParameter('base.full_url') . '/not-found/404';
        }

        $generator = new UrlGenerator(self::$routing->getRouteCollection(), self::$routing->getContext());

        if (Config::getParameter('router.use_httpd_rewrite') === true) {
            return $generator->generate($route_name, $arguments, $generator::ABSOLUTE_URL);
        }

        $prefix = '/index.php?/';

        if ($route_name === 'home') {
            $prefix = '/';
        }

        return Config::getParameter('base.full_url') . $prefix . $generator->generate($route_name, $arguments, $generator::RELATIVE_PATH);
    }

    /**
     * Create path
     *
     * @param string $path
     * @return string
     */
    public static function createPath($path = null)
    {
        if (isset(self::$options['directory']) === false || self::$options['directory'] == null) {
            return false;
        }

        if ($path === null) {
            return self::$options['directory'];
        }

        $path = str_replace('/', DIRECTORY_SEPARATOR, $path);

        return self::$options['directory'] . $path;
    }

    /**
     * Get application environment
     *
     * @return string
     */
    public function getEnv()
    {
        $available_environments = ['dev', 'prod', 'test'];

        if (isset(self::$options['environment']) === false || self::$options['environment'] == null
            || in_array(self::$options['environment'], $available_environments, true) === false
        ) {
            return 'prod';
        }

        return self::$options['environment'];
    }

    /**
     * Is CLI request
     *
     * @return bool
     */
    public function inCli()
    {
        if (isset(self::$options['in_cli']) && self::$options['in_cli'] === true) {
            return true;
        }

        return false;
    }

    /**
     * Get application version
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Get memory usage
     *
     * @return int
     */
    public function getUsageMemory()
    {
        return memory_get_usage(true);
    }

    /**
     * In travis
     *
     * @return bool
     */
    public function inTravis()
    {
        return getenv('TRAVIS') ? true : false;
    }

    /**
     * Get all datasource queries 
     * 
     * @return int
     */
    public function dataSourceQueries()
    {
        return self::getInstance()->get('datasource')->getQueriesNumber();
    }
}
