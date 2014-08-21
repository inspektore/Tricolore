<?php
namespace Tricolore\Services;

use Tricolore\Application;
use Tricolore\Exception\ServicesException;
use Symfony\Component\Yaml\Yaml;

abstract class ServiceLocator
{
    /**
     * Get the service
     * 
     * @param string $key 
     * @return mixed
     */
    public function get($key)
    {
        $service_map = Yaml::parse(Application::createPath('library:Tricolore:Services:ServicesMap:Map.yml'));

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
                $service_map['service-locator'][$key]['function'], get_class($service_load)));
        }

        return call_user_func([$service_load, $service_map['service-locator'][$key]['function']]);
    }
}
