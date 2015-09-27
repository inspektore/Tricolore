<?php
namespace Tricolore\Config;

use Tricolore\Foundation\Application;
use Symfony\Component\Yaml\Yaml;

class Config
{
    /**
     * Get parameter
     * 
     * @param string $key
     * @return string|bool
     */
    public static function getParameter($key, $collection = 'configuration')
    {
        if (self::collectionExists($collection) === false) {
            return false;
        }

        $collection_parsed = Yaml::parse(file_get_contents(sprintf(Application::createPath('app/config/%s.yml'), $collection)));

        if (isset($collection_parsed[Application::getInstance()->getEnv()][$key]) === false) {
            return false;
        }

        return $collection_parsed[Application::getInstance()->getEnv()][$key];
    }

    /**
     * Fech all parameters as array
     * 
     * @param string $collection 
     * @return array
     */
    public static function all($collection)
    {
        if (self::collectionExists($collection) === false ||
            !count($collection_parsed = Yaml::parse(
                file_get_contents(sprintf(Application::createPath('app/config/%s.yml'), $collection))))
        ) {
            return [];
        }

        return $collection_parsed;
    }

    /**
     * Collection exists
     * 
     * @param $collection
     * @return bool
     */
    private static function collectionExists($collection)
    {
        return file_exists(sprintf(Application::createPath('app/config/%s.yml'), $collection));
    }
}
