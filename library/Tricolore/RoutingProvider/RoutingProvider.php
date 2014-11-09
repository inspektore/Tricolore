<?php
namespace Tricolore\RoutingProvider;

use Tricolore\Application;
use Tricolore\Config\Config;
use Tricolore\Services\ServiceLocator;
use Tricolore\Exception\RuntimeException;
use Symfony\Component\HttpFoundation\Request;
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
        
        if(endsWith('/', $request) === true) {
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

        $this->context->fromRequest(Request::createFromGlobals());

        $matcher = new UrlMatcher($this->collection, $this->context);

        if(Application::getInstance()->getEnv() !== 'test') {
            $this->controllerCall($matcher, $request);
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
     * @throws Tricolore\Exception\RuntimeException
     * @return void
     */
    private function controllerCall(UrlMatcher $matcher, $request)
    {
        try {
            $parameters = $matcher->match($request);

            $controller = explode(':', $parameters['_controller']);

            if(class_exists($controller[0]) === false) {
                throw new RuntimeException(sprintf('Class "%s" does not exists.', $controller[0]));
            }

            $call = new $controller[0]();

            $arguments = [];
            
            foreach($parameters as $key => $value) {
                if($key[0] === '_') {
                    continue;
                }

                $arguments[$key] = $value;
            }

            if(method_exists($call, $controller[1]) === false) {
                throw new RuntimeException(sprintf('Action method "%s" in "%s" does not exists.', $controller[1], $controller[0]));
            }

            return call_user_func([$call, $controller[1]], (object)$arguments);
        } catch(ResourceNotFoundException $exception) {
            http_response_code(404);

            $this->get('view')->display('Errors', 'ResourceNotFound');
        }
    }
}
