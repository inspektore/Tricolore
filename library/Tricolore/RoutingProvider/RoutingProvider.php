<?php
namespace Tricolore\RoutingProvider;

use Tricolore\Config\Config;
use Tricolore\Services\ServiceLocator;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class RoutingProvider extends ServiceLocator
{
    /**
     * Register routing
     *  
     * @return void
     */
    public function register()
    {
        $request = getenv('QUERY_STRING');

        if($request == null) {
            $request = '/';
        }

        $locator = new FileLocator([__DIR__]);
        $loader = new YamlFileLoader($locator);
        $collection = $loader->load('RouteCollection/Collection.yml');

        $collection->addPrefix(Config::key('router.prefix'));
        $collection->setHost(Config::key('router.host'));

        $context = new RequestContext(Config::key('base.full_url'));
        $matcher = new UrlMatcher($collection, $context);

        $this->callToController($matcher, $request);
    }

    /**
     * Call to the controller
     * 
     * @param UrlMatcher $matcher
     * @param string $request
     * @return void
     */
    private function callToController(UrlMatcher $matcher, $request)
    {
        try {
            $parameters = $matcher->match($request);

            $controller = new $parameters['controller']();

            $arguments = $parameters;

            unset($arguments['action'],
            $arguments['controller'],
            $arguments['resource'],
            $arguments['type'],
            $arguments['prefix'],
            $arguments['pattern'],
            $arguments['path'],
            $arguments['host'],
            $arguments['schemes'],
            $arguments['methods'],
            $arguments['defaults'],
            $arguments['requirements'],
            $arguments['options'],
            $arguments['condition'],
            $arguments['_route']);

            return call_user_func_array([$controller, $parameters['action']], $arguments);
        } catch(ResourceNotFoundException $exception) {
            $this->get('view')->getEnv()->loadTemplate('Errors/ResourceNotFound.html.twig')->display([]);
        }
    }
}
