<?php
namespace Tricolore\Config;

use Tricolore\Application;
use Tricolore\View\RenderException;
use Tricolore\Exception\ConfigException;
use Symfony\Component\Yaml\Yaml;

class Config
{
    /**
     * Get the config by key
     * 
     * @param string $key
     * @throws Tricolore\Exception\ConfigException
     * @return mixed
     */
    public static function key($key, $collection = 'Configuration')
    {
        if(file_exists(sprintf(
            Application::createPath('library:Tricolore:Config:Resources:%s.yml'), $collection)) === false
        ) {
            throw new ConfigException(sprintf('Configuration file: %s.yml does not exists.', $collection));
        }

        $collection_yml = Yaml::parse(sprintf(Application::createPath('library:Tricolore:Config:Resources:%s.yml'), $collection));

        if(isset($collection_yml[$key]) === false) {
            return false;
        }      

        return $collection_yml[$key];      
    }
}
