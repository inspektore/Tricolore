<?php
namespace Tricolore\Services;

use Tricolore\Foundation\Application;
use Tricolore\Exception\ServicesException;
use Tricolore\Exception\NotFoundResourceException;
use Symfony\Component\Yaml\Yaml;

abstract class ServiceLocator
{
    /**
     * Get the service
     * 
     * @param string $key 
     * @param array $arguments
     * @param string $service_file
     * @throws Tricolore\Exception\ServicesException
     * @return object
     */
    final public function get($key, array $arguments = [], $service_file = null)
    {
        $service_map = $this->parseServicesMap($service_file);

        if (isset($service_map['services'][$key]) === false) {
            throw new ServicesException(sprintf('Service "%s" not exists.', $key));
        }

        if (class_exists($service_map['services'][$key]['class']) === false) {
            throw new ServicesException(sprintf('Class "%s" not exists.', $service_map['services'][$key]['class']));
        }

        $service_load = $this->serviceClassLoad($service_map, $key);

        if (isset($service_map['services'][$key]['function']) === false) {
            return $service_load;
        }

        if (method_exists($service_load, $service_map['services'][$key]['function']) === false) {
            throw new ServicesException(sprintf('Method "%s" in class "%s" not exists.', 
                $service_map['services'][$key]['function'], $service_map['services'][$key]['class']));
        }

        if ($this->isStatic($service_map, $key) === true) {
            return $this->callStatic([$service_load, $service_map['services'][$key]['function']], $arguments);
        }

        return $this->callDynamic([$service_load, $service_map['services'][$key]['function']], $arguments);
    }

    /**
     * Call dymanic
     * 
     * @param callable $callback
     * @param array $arguments
     * @return object
     */
    private function callDynamic(callable $callback, array $arguments)
    {
        return call_user_func_array($callback, $arguments);
    }

    /**
     * Call static
     * 
     * @param callable $callback
     * @param array $arguments
     * @return object
     */
    private function callStatic(callable $callback, array $arguments)
    {
        return forward_static_call_array($callback, $arguments);
    }

    /**
     * Is static
     * 
     * @param array $service_map
     * @param string $key
     * @return bool
     */
    private function isStatic(array $service_map, $key)
    {
        return isset($service_map['services'][$key]['static']) && $service_map['services'][$key]['static'] === true;
    }

    /**
     * Service class load
     * 
     * @param array $service_map
     * @param string $key 
     * @return object
     */
    private function serviceClassLoad(array $service_map, $key)
    {
        if ($this->isStatic($service_map, $key) === true) {
            return $service_map['services'][$key]['class'];
        }

        return new $service_map['services'][$key]['class'];
    }

    /**
     * Parse YAML file to raw array
     * 
     * @param string $service_file
     * @throws Tricolore\Exception\NotFoundResourceException
     * @return array
     */
    private function parseServicesMap($service_file)
    {
        if ($service_file === null) {
            $service_map = Yaml::parse(file_get_contents(Application::createPath('app:services:services.yml')));
        } else {
            if (file_exists($service_file) === false) {
                throw new NotFoundResourceException(sprintf('File "%s" does not exists.', $service_file));
            }

            $service_map = Yaml::parse(file_get_contents($service_file)); 
        }

        return $service_map;
    }
}
