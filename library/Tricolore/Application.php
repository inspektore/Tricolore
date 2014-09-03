<?php
namespace Tricolore;

use Tricolore\Exception\ApplicationException;
use Tricolore\Services\AutoloadService;

class Application
{
    /**
     * Application options
     * 
     * @var array
     */
    private static $options;

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

        $services = new AutoloadService();
        $services->dispatch();
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
     * Create path
     * 
     * @param string $path 
     * @throws Tricolore\Exception\ApplicationException
     * @return string
     */
    public static function createPath($path = null)
    {
        if(isset(self::$options['directory']) === false || self::$options['directory'] == null) {
            throw new ApplicationException('Base directory are not defined in Application options');
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
}
