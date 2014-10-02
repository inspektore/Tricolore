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
        
        if(file_exists(sprintf(
            Application::createPath('library:Tricolore:Config:Resources:%s.yml'), $collection)) === false
        ) {
            return false;
        }

        $collection_yml = Yaml::parse(sprintf(Application::createPath('library:Tricolore:Config:Resources:%s.yml'), $collection));

        if(isset($collection_yml[$key]) === false) {
            return false;
        }

        return $collection_yml[$key];      
    }
}
