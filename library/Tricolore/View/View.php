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

        $in_dev = Application::getInstance()->getEnv() === 'dev';

        $this->environment = new \Twig_Environment($loader, [
            'cache' => ($in_dev) ? Application::createPath('storage:twig') : false,
            'auto_reload' => ($in_dev) ?: false,
            'strict_variables' => ($in_dev) ?: false
        ]);

        $this->registerGlobals();
        $this->registerFunctions();
        $this->formIntegration();
        $this->transIntegration();

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

        $combined_template_path = $template_section . '/' . $template_name;

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
     * Translation integration
     * 
     * @return void
     */
    private function transIntegration()
    {
        $this->environment->addExtension(new TranslationExtension($this->get('translator')));
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
