<?php
namespace Tricolore\Services;

use Tricolore\Application;
use Tricolore\Exception\ServicesException;
use Tricolore\Exception\AssetNotFound;
use Symfony\Component\Yaml\Yaml;

class AutoloadService
{
    /**
     * Dispatch classes
     * 
     * @param string $path
     * @throws Tricolore\Exception\ServicesException
     * @throws Tricolore\Exception\AssetNotFound
     * @return array
     */
    public function dispatch($path = null)
    {
        if($path === null) {
            $service_map = Yaml::parse(Application::createPath('library:Tricolore:Services:ServicesMap:Map.yml'));
        } else {
            if(file_exists($path) === false) {
                throw new AssetNotFound(sprintf('File: %s does not exists.', $path));
            }

            $service_map = Yaml::parse($path);
        }

        if(!count($service_map['service-autoload'])) {
            return false;
        }

        $loaded_classes = [];

        foreach($service_map['service-autoload'] as $key => $class) {
            $loaded_classes[] = $class['class'] . (isset($class['function']) === true ? ':' . $class['function'] : null);
            
            if(class_exists($class['class']) === false) {
                throw new ServicesException(sprintf('Class "%s" not exists.', $class['class']));
            }

            $service = new $class['class'];

            if(isset($class['function']) === false) {
                $service;

                continue;
            }

            if(method_exists($service, $class['function']) === false) {
                throw new ServicesException(sprintf('Method "%s" in class "%s" not exists.', $class['function'], $class['class']));
            }

            call_user_func([$service, $class['function']]);
        }

        return $loaded_classes;
    }
}
