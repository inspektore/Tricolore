<?php
namespace Tricolore\View;

use Tricolore\Application;
use Tricolore\Config\Config;
use Tricolore\Services\ServiceLocator;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Form\TwigRenderer;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Symfony\Bridge\Twig\Extension\TranslationExtension;

class View extends ServiceLocator
{
    /**
     * Twig Environment
     *  
     * @var \Twig_Environment
     */
    private $environment;

    /**
     * Integrate with Twig
     * 
     * @param bool $safe_mode
     * @return Tricolore\View
     */
    public function register($safe_mode = false)
    {
        \Twig_Autoloader::register();

        $finder = $this->get('finder')
        ->directories()
        ->in(Application::createPath('library:Tricolore:View:Templates'));

        foreach($finder as $file) {
            $directories[] = $file->getRealpath();
        }

        $directories = array_merge($directories, [
            Application::createPath('library:Tricolore:View:Templates')
        ]);

        if(Application::getInstance()->getEnv() === 'test') {
            $directories = array_merge($directories, [
                Application::createPath('library:Tricolore:Tests:Fixtures:Templates') 
            ]);
        }

        $loader = new \Twig_Loader_Filesystem($directories);

        $in_dev = Application::getInstance()->getEnv() === 'dev';

        $this->environment = new \Twig_Environment($loader, [
            'cache' => ($in_dev) ? Application::createPath('storage:twig') : false,
            'auto_reload' => ($in_dev) ?: false,
            'strict_variables' => ($in_dev) ?: false
        ]);

        $this->registerGlobals();
        $this->registerFunctions();

        if($safe_mode === false) {
            $this->formIntegration();
            $this->transIntegration();
        }

        return $this;
    }

    /**
     * Render and display
     * 
     * @param string $template_section
     * @param string $template_name
     * @param array $variables
     * @param bool $return
     * @return void
     */
    public function display($template_section, $template_name, array $variables = [], $return = false)
    {
        if(substr($template_name, -10) !== '.html.twig') {
            $template_name .= '.html.twig';
        }

        $combined_template_path = ($template_section != null ? $template_section . '/' : null) . $template_name;

        if($return === true) {
            return $this->environment->loadTemplate($combined_template_path)->render($variables);
        }

        return $this->environment->loadTemplate($combined_template_path)->display($variables);
    }

    /**
     * Register global variables
     * 
     * @return void
     */
    private function registerGlobals()
    {
        $this->environment->addGlobal('app', Application::getInstance());
    }

    /**
     * Register functions
     *  
     * @return void
     */
    private function registerFunctions()
    {
        $this->environment->addFunction(new \Twig_SimpleFunction('config', function ($key) {
            return Config::key($key);
        }));

        $this->environment->addFunction(new \Twig_SimpleFunction('assets', function ($section, $file) {
            return Config::key('base.full_url') . '/assets/' . $section . '/' . $file;
        }));

        $this->environment->addFunction(new \Twig_SimpleFunction('url', function ($route_name = null, $arguments = []) {
            return Application::getInstance()->buildUrl($route_name, $arguments);
        }));
    }

    /**
     * Form integration
     * 
     * @return void
     */
    private function formIntegration()
    {
        $form = new TwigRendererEngine(['form_div_layout.html.twig']);
        $form->setEnvironment($this->environment);

        $this->environment->addExtension(new FormExtension(new TwigRenderer($form)));        
    }

    /**
     * Translation integration
     * 
     * @return void
     */
    private function transIntegration()
    {
        if(Application::getInstance()->getEnv() === 'test') {
            $this->environment->addExtension(new TranslationExtension($this->get('translator', [
                Application::createPath('library:Tricolore:Tests:Fixtures:Translation_enEN.xliff'),
                'en_EN'
            ])));
        } else {
            $this->environment->addExtension(new TranslationExtension($this->get('translator')));
        }
    }

    /**
     * Accessor for \Twig_Environment
     * 
     * @return \Twig_Environment
     */
    public function getEnv()
    {
        return $this->environment;
    }

    /**
     * Handle exception
     * 
     * @param $exception 
     * @return void
     */
    public function handleException($exception)
    {
        http_response_code(500);

        if(Application::getInstance()->getEnv() === 'prod') {
            return $this->display('Exceptions', 'HandleClientException');
        }

        $exception_name = null;

        if(method_exists($exception, 'getExceptionName')) {
            $exception_name = $exception->getExceptionName();
        }

        $error_file = $exception->getFile();
        $error_line = $exception->getLine();

        if($exception_name === 'ErrorException') {
            $error_file = $exception->getErrorFile();
            $error_line = $exception->getErrorLine();
        }

        $file_array = new \SplFileObject($error_file, 'r');

        $request = $this->get('request');
        $request = $request::createFromGlobals();

        return $this->display('Exceptions', 'HandleDevException', [
            'exception' => $exception,
            'file_array' => $file_array,
            'error_line' => $error_line,
            'error_file' => $error_file,
            'exception_name' => $exception_name,
            'path_info' => $request->getPathInfo(),
            'useragent' => $request->server->all()['HTTP_USER_AGENT'],
            'server_os' => $request->server->all()['SERVER_SOFTWARE']
        ]);
    }
}
