<?php
namespace Tricolore\View;

use Tricolore\Foundation\Application;
use Tricolore\Config\Config;
use Tricolore\Services\ServiceLocator;
use Tricolore\Session\Session;
use Tricolore\Member\Member;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Form\TwigRenderer;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Cocur\Slugify\Bridge\Twig\SlugifyExtension;
use Cocur\Slugify\Slugify;

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
        $this->environment = new \Twig_Environment($this->getLoader(), $this->environmentOptions());

        $this->registerGlobals();
        $this->registerFunctions();
        $this->registerExtensions();

        if ($safe_mode === false) {
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
     * @return string|bool
     */
    public function display($template_section, $template_name, array $variables = [], $return = false)
    {
        if (endsWith('.html.twig', $template_name, 10) === false) {
            $template_name .= '.html.twig';
        }

        $combined_template_path = ($template_section != null ? $template_section . '/' : null) . $template_name;

        if (Config::getParameter('gzip.enabled') === true && extension_loaded('zlib') === true) {
            ob_start('ob_gzhandler');

            if ($return === true) {
                $this->environment->loadTemplate($combined_template_path)->render($variables);
            } else {
                $this->environment->loadTemplate($combined_template_path)->display($variables);
            }

            header('Connection: close');

            ob_end_flush();

            return;
        }

        if ($return === true) {
            return $this->environment->loadTemplate($combined_template_path)->render($variables);
        }

        return $this->environment->loadTemplate($combined_template_path)->display($variables);
    }

    /**
     * Get loader
     * 
     * @return \Twig_Loader_Filesystem
     */
    private function getLoader()
    {
        $finder = $this->get('finder')
            ->directories()
            ->in(Application::createPath('src:View:Templates'));

        foreach ($finder as $file) {
            $directories[] = $file->getRealpath();
        }

        $directories = array_merge($directories, [
            Application::createPath('src:View:Templates')
        ]);

        if (Application::getInstance()->getEnv() === 'test') {
            $directories = array_merge($directories, [
                Application::createPath('src:Tests:Fixtures:Templates') 
            ]);
        }

        return new \Twig_Loader_Filesystem($directories);
    }

    /**
     * Environment options
     * 
     * @return array
     */
    private function environmentOptions()
    {
        $in_prod = Application::getInstance()->getEnv() === 'prod';
        $cache_directory = Application::createPath(Config::getParameter('directory.storage') . ':twig');

        return [
            'cache' => ($in_prod === true) ? $cache_directory : false,
            'auto_reload' => ($in_prod === true) ? false : true,
            'strict_variables' => ($in_prod === true) ? false : true,
            'debug' => ($in_prod === true) ? false : true        
        ];
    }

    /**
     * Register global variables
     * 
     * @return void
     */
    private function registerGlobals()
    {
        $this->environment->addGlobal('app', Application::getInstance());
        $this->environment->addGlobal('session', Session::getSession());
        $this->environment->addGlobal('member', Member::getInstance());
    }

    /**
     * Register extensions
     * 
     * @return void
     */
    private function registerExtensions()
    {
        if (Application::getInstance()->getEnv() !== 'prod') {
            $this->environment->addExtension(new \Twig_Extension_Debug());
        }

        $this->environment->addExtension(new SlugifyExtension(Slugify::create()));
    }

    /**
     * Register functions
     *  
     * @return void
     */
    private function registerFunctions()
    {
        $this->environment->addFunction(new \Twig_SimpleFunction('config', function ($key) {
            return Config::getParameter($key);
        }));

        $this->environment->addFunction(new \Twig_SimpleFunction('assets', function ($section, $file) {
            return Config::getParameter('base.full_url') . '/' . Config::getParameter('directory.assets') . '/' . $section . '/' . $file;
        }));

        $this->environment->addFunction(new \Twig_SimpleFunction('url', function ($route_name = null, $arguments = []) {
            return Application::getInstance()->buildUrl($route_name, $arguments);
        }));
    }

    /**
     * Integrate with forms
     * 
     * @return void
     */
    private function formIntegration()
    {
        $form = new TwigRendererEngine(['bootstrap_3_layout.html.twig']);
        $form->setEnvironment($this->environment);

        $this->environment->addExtension(new FormExtension(new TwigRenderer($form, Session::csrfProvider())));        
    }

    /**
     * Integrate with translator
     * 
     * @return void
     */
    private function transIntegration()
    {
        if (Application::getInstance()->getEnv() === 'test') {
            $this->environment->addExtension(new TranslationExtension($this->get('translator', [
                Application::createPath('src:Tests:Fixtures:Translation_enEN.xliff'),
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
}
