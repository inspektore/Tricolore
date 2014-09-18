<?php
namespace Tricolore;

use Tricolore\Config\Config;
use Tricolore\Exception\ApplicationException;
use Tricolore\RoutingProvider\RoutingProvider;
use Tricolore\View\View;
use Symfony\Component\Routing\Generator\UrlGenerator;

class Application
{
    /**
     * Application options
     * 
     * @var array
     */
    private static $options;

    /**
     * Routing object
     * 
     * @var Tricolore\RoutingProvider\RoutingProvider
     */
    private static $routing;

    /**
     * Register application and services
     *
     * @param array $options
     * @return void
     */
    public static function register(array $options)
    {
        self::$options = $options;

        if(self::getInstance()->getEnv() === 'test') {
            return false;
        }

        try {
            self::$routing = new RoutingProvider();
            self::$routing->register();            
        } catch(\Exception $exception) {
            (new View)->register(true)->handleException($exception);
        }
    }

    /**
     * Application instance
     * 
     * @return Tricolore\Application
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
        if($route_name == null) {
            return Config::key('base.full_url');
        }

        $generator = new UrlGenerator(self::$routing->getRouteCollection(), self::$routing->getContext());

        return $generator->generate($route_name, $arguments);
    }

    /**
     * Create path
     * 
     * @param string $path 
     * @throws Tricolore\Exception\ApplicationException
     * @return string
     */
    public static function createPath($path = null)
    {
        if(isset(self::$options['directory']) === false || self::$options['directory'] == null) {
            return false;
        }

        if($path === null) {
            return self::$options['directory'];
        }

        $path = str_replace(':', DIRECTORY_SEPARATOR, $path);

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

        if(isset(self::$options['environment']) === false || self::$options['environment'] == null
            || in_array(self::$options['environment'], $available_environments, true) === false
        ) {
            return 'prod';
        }

        return self::$options['environment'];
    }

    /**
     * Get application version
     * 
     * @return string
     */
    public function getVersion()
    {
        if(isset(self::$options['version']) === false || self::$options['version'] == null) {
            return 'undefined';
        }

        return self::$options['version'];
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
}
