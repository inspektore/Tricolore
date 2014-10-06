<?php
namespace Tricolore\Config;

use Tricolore\Application;
use Symfony\Component\Yaml\Yaml;

class Config
{
    /**
     * Get the config by key
     * 
     * @param string $key
     * @return mixed
     */
    public static function key($key, $collection = 'Configuration')
    {
        if(Application::getInstance()->getEnv() === 'test') {
            $collection = 'TestConfiguration';
        }
        
        if(self::collectionExists($collection) === false) {
            return false;
        }

        $collection_parsed = Yaml::parse(sprintf(Application::createPath('library:Tricolore:Config:Resources:%s.yml'), $collection));

        if(isset($collection_parsed[$key]) === false) {
            return false;
        }

        return $collection_parsed[$key];
    }

    /**
     * Fech all as array
     * 
     * @param string $collection 
     * @return array
     */
    public static function all($collection)
    {
        if(self::collectionExists($collection) === false ||
            !count($collection_parsed = Yaml::parse(
                sprintf(Application::createPath('library:Tricolore:Config:Resources:%s.yml'), $collection)))
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
        return file_exists(sprintf(Application::createPath('library:Tricolore:Config:Resources:%s.yml'), $collection));
    }
}
