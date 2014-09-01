<?php
namespace Tricolore\Services;

use Tricolore\Application;
use Tricolore\Exception\ServicesException;
use Symfony\Component\Yaml\Yaml;

class AutoloadService
{
    /**
     * Dispatch classes
     * 
     * @throws Tricolore\Exception\ServicesException
     * @return void
     */
    public function dispatch()
    {
        $service_map = Yaml::parse(Application::createPath('library:Tricolore:Services:ServicesMap:Map.yml'));

        if(!count($service_map['service-autoload'])) {
            return false;
        }

        foreach($service_map['service-autoload'] as $key => $class) {
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
    }
}
