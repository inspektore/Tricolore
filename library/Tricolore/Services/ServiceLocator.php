<?php
namespace Tricolore\Services;

use Tricolore\Application;
use Tricolore\Exception\ServicesException;
use Tricolore\Exception\AssetNotFound;
use Symfony\Component\Yaml\Yaml;

abstract class ServiceLocator
{
    /**
     * Get the service
     * 
     * @param string $key 
     * @param string $service_file
     * @throws Tricolore\Exception\ServicesException
     * @throws Tricolore\Exception\AssetNotFound
     * @return mixed
     */
    public function get($key, $service_file = null)
    {
        if($service_file === null) {
            $service_map = Yaml::parse(Application::createPath('library:Tricolore:Services:ServicesMap:Map.yml'));
        } else {
            if(file_exists($service_file) === false) {
                throw new AssetNotFound(sprintf('File: %s does not exists.', $service_file));
            }

            $service_map = Yaml::parse($service_file); 
        }
        
        if(isset($service_map['service-locator'][$key]) === false) {
            throw new ServicesException(sprintf('Service "%s" not exists.', $key));
        }

        if(class_exists($service_map['service-locator'][$key]['class']) === false) {
            throw new ServicesException(sprintf('Class "%s" not exists.', $service_map['service-locator'][$key]['class']));
        }

        $service_load = new $service_map['service-locator'][$key]['class'];

        if(isset($service_map['service-locator'][$key]['function']) === false) {
            return $service_load;
        }

        if(method_exists($service_load, $service_map['service-locator'][$key]['function']) === false) {
            throw new ServicesException(sprintf('Method "%s" in class "%s" not exists.', 
                $service_map['service-locator'][$key]['function'], $service_map['service-locator'][$key]['class']));
        }

        return call_user_func([$service_load, $service_map['service-locator'][$key]['function']]);
    }
}
