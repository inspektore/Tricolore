<?php
namespace Tricolore\RoutingProvider;

use Tricolore\Application;
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
     * Route collection
     * 
     * @var Symfony\Component\Routing\RouteCollection
     */
    private $collection;

    /**
     * Request context
     * 
     * @var Symfony\Component\Routing\RequestContext
     */    
    private $context;

    /**
     * Register routing
     * 
     * @param bool $silent
     * @return void
     */
    public function register()
    {
        $request = getenv('QUERY_STRING');
        
        if(substr($request, -1) === '/') {
            $request = substr($request, 0, -1);
        }

        if($request == null) {
            $request = '/';
        }

        $locator = new FileLocator([__DIR__]);
        $loader = new YamlFileLoader($locator);

        $collection_filename = 'Collection';

        if(Application::getInstance()->getEnv() === 'test') {
            $collection_filename = 'TestCollection';
        }

        $this->collection = $loader->load(sprintf('RouteCollection/%s.yml', $collection_filename));
        $this->collection->addPrefix(Config::key('router.prefix'));
        $this->collection->setHost(Config::key('router.host'));

        $this->context = new RequestContext();

        $http_foundation = $this->get('request');
        $this->context->fromRequest($http_foundation::createFromGlobals());

        $matcher = new UrlMatcher($this->collection, $this->context);

        if(Application::getInstance()->getEnv() !== 'test') {
            $this->callToController($matcher, $request);
        }
    }

    /**
     * Route collection
     *  
     * @return Symfony\Component\Routing\RouteCollection
     */
    public function getRouteCollection()
    {
        return $this->collection;
    }

    /**
     * Request context
     * 
     * @return Symfony\Component\Routing\RequestContext
     */
    public function getContext()
    {
        return $this->context;
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

            $controller = explode(':', $parameters['_controller']);
            $call = new $controller[0]();

            $arguments = [];
            
            foreach($parameters as $key => $value) {
                if($key[0] === '_') {
                    continue;
                }

                $arguments[$key] = $value;
            }

            return call_user_func([$call, $controller[1]], (object)$arguments);
        } catch(ResourceNotFoundException $exception) {
            $this->get('view')->display('Errors', 'ResourceNotFound');
        }
    }
}
