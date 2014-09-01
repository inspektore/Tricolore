<?php
namespace Tricolore\View;

use Tricolore\Application;
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

        $twig_form_template_dir = Application::createPath('library:Symfony:Bridge:Twig:Resources:views:Form');

        $loader = new \Twig_Loader_Filesystem([
            Application::createPath('library:Tricolore:View:Templates'),
            $twig_form_template_dir
        ]);

        $in_dev = (Application::getInstance()->getEnv() === 'dev') ? true : false;

        $this->environment = new \Twig_Environment($loader, [
            'cache' => ($in_dev) ? Application::createPath('storage:twig') : false,
            'auto_reload' => ($in_dev) ?: false,
            'strict_variables' => ($in_dev) ?: false
        ]);

        $form = new TwigRendererEngine(['form_div_layout.html.twig']);
        $form->setEnvironment($this->environment);

        $this->environment->addExtension(new FormExtension(new TwigRenderer($form)));

        return $this;
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
