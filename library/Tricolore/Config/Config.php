<?php
namespace Tricolore\Config;

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
    public static function get($key, $collection = 'Configuration')
    {
        if(file_exists(sprintf(
            Application::createPath('library:Tricolore:Config:Resources:%s.yml'), $collection)) === false
        ) {
            throw new ConfigException(sprintf('Configuration file: %s.yml does not exists.', $collection));
        }

        return Yaml::parse(Application::createPath('library:Tricolore:Config:Resources:%s.yml'));
    }
}
