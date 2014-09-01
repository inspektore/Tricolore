<?php
namespace Tricolore\View;

use Tricolore\Application;
use Tricolore\Config\Config;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Form\TwigRenderer;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;

class View
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
     * @return Tricolore\View
     */
    public function register()
    {
        \Twig_Autoloader::register();

        $loader = new \Twig_Loader_Filesystem([
            Application::createPath('library:Tricolore:View:Templates'),
            Application::createPath('library:Tricolore:View:Templates:Actions'),
            Application::createPath('library:Tricolore:View:Templates:Blocks'),
            Application::createPath('library:Tricolore:View:Templates:Errors'),
            Application::createPath('library:Tricolore:View:Templates:Macros'),
            Application::createPath('library:Symfony:Bridge:Twig:Resources:views:Form')
        ]);

        $in_dev = (Application::getInstance()->getEnv() === 'dev') ? true : false;

        $this->environment = new \Twig_Environment($loader, [
            'cache' => ($in_dev) ? Application::createPath('storage:twig') : false,
            'auto_reload' => ($in_dev) ?: false,
            'strict_variables' => ($in_dev) ?: false
        ]);

        $this->registerGlobals();
        $this->registerFunctions();
        $this->formIntegration();

        return $this;
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
            return Config::key('base.full_url') . 'assets/' . $section . '/' . $file;
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
     * Accessor for \Twig_Environment
     * 
     * @return \Twig_Environment
     */
    public function getEnv()
    {
        return $this->environment;
    }
}
