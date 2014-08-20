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

        $service_load = new $service_map['service-locator'][$key]['class'];

        return call_user_func([$service_load, $service_map['service-locator'][$key]['function']]);
    }
}
