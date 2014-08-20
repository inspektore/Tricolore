<?php
namespace Tricolore\View;

use Tricolore\Application;

class View
{
    /**
     * Twig Loader
     *  
     * @var \Twig_Loader_Filesystem
     */
    private $loader;

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

        $this->loader = new \Twig_Loader_Filesystem(Application::createPath('library:Tricolore:View:Templates'));

        $in_dev = (Application::getInstance()->getEnv() === 'dev') ? true : false;

        $this->environment = new \Twig_Environment($this->loader, [
            'cache' => ($in_dev) ? Application::createPath('storage:twig') : false,
            'auto_reload' => ($in_dev) ?: false,
            'strict_variables' => ($in_dev) ?: false
        ]);

        return $this;
    }
}
