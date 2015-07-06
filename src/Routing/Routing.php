<?php
namespace Tricolore\Routing;

use Tricolore\Foundation\Application;
use Tricolore\Config\Config;
use Tricolore\Services\ServiceLocator;
use Tricolore\Exception\RuntimeException;
use Tricolore\Exception\NoPermissionException;
use phpDocumentor\Reflection\DocBlock;
use Symfony\Component\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

class Routing extends ServiceLocator
{
    /**
     * Router
     * 
     * @var Symfony\Component\Routing\Router
     */    
    private $router;

    /**
     * Register routing
     * 
     * @codeCoverageIgnore
     * @return void
     */
    public function register()
    {
        $request = getenv('QUERY_STRING');

        if (endsWith('/', $request) === true) {
            $request = substr($request, 0, -1);
        }

        if ($request == null) {
            $request = '/';
        }

        $locator = new FileLocator([Application::createPath('app')]);
        $loader = new YamlFileLoader($locator);

        $collection_filename = strtolower(Application::getInstance()->getEnv());

        $request_context = new RequestContext();
        $request_context->fromRequest(Request::createFromGlobals());

        $in_prod = Application::getInstance()->getEnv() === 'prod';

        $this->router = new Router($loader, sprintf('routing/%s.yml', $collection_filename), [
            'cache_dir' => ($in_prod === true) ? Application::createPath(Config::getParameter('directory.storage') . ':router') : null
        ], $request_context);

        if (Application::getInstance()->getEnv() !== 'test') {
            $this->call($this->router, $request);
        }
    }

    /**
     * Route collection
     * 
     * @codeCoverageIgnore
     * @return Symfony\Component\Routing\RouteCollection
     */
    public function getRouteCollection()
    {
        return $this->router->getRouteCollection();
    }

    /**
     * Request context
     * 
     * @codeCoverageIgnore
     * @return Symfony\Component\Routing\RequestContext
     */
    public function getContext()
    {
        return $this->router->getContext();
    }

    /**
     * Call to the controller
     * 
     * @codeCoverageIgnore
     * @param Router $router
     * @param string $request
     * @throws Tricolore\Exception\RuntimeException
     * @return void
     */
    private function call(Router $router, $request)
    {
        try {
            $parameters = $router->match($request);

            $controller = explode(':', $parameters['_controller']);

            if (class_exists($controller[0]) === false) {
                throw new RuntimeException(sprintf('Class "%s" does not exists.', $controller[0]));
            }

            $call = new $controller[0]();

            $arguments = [];
            
            foreach ($parameters as $key => $value) {
                if ($key[0] === '_') {
                    continue;
                }

                $arguments[$key] = $value;
            }

            if (method_exists($call, $controller[1]) === false) {
                throw new RuntimeException(sprintf('Action method "%s" in "%s" does not exists.', $controller[1], $controller[0]));
            }

            $this->checkControllerAccess($call, $controller[1]);

            return call_user_func_array([$call, $controller[1]], $arguments);
        } catch (ResourceNotFoundException $exception) {
            http_response_code(404);

            $this->get('view')->display('Errors', 'ResourceNotFound');
        } catch (MethodNotAllowedException $exception) {
            http_response_code(405);

            $render = [
                'method' => $this->getContext()->getMethod()
            ];

            $this->get('view')->display('Errors', 'MethodNotAllowed', $render);
        }
    }

    /**
     * Check access for controller
     * 
     * @param object $controller
     * @param string $method
     * @throws Tricolore\Exception\NoPermissionException
     * @return void
     */
    private function checkControllerAccess($controller, $method)
    {
        $class = new \ReflectionClass(get_class($controller));
        $phpdoc = new DocBlock($class->getMethod($method)->getDocComment());
        $tag = $phpdoc->getTagsByName('Access');

        if (!count($tag)) {
            throw new NoPermissionException('You have no permission to do this');
        }

        $access = $tag[0]->getDescription();

        if ($this->get('acl.manager')->isGranded($access) === false) {
            throw new NoPermissionException('You have no permission to do this');
        }
    }
}
