<?php
namespace Tricolore\Controller\Installer;

use Tricolore\Controller\ControllerAbstract;
use Tricolore\Installer\Installer;
use Tricolore\Form\FormTypes\Installer\InstallerType;
use Symfony\Component\HttpFoundation\Request;

class Install extends ControllerAbstract
{
    /**
     * Installer
     *
     * @var Tricolore\Installer\Installer
     */
    private $installer;
    
    /**
     * Construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->installer = new Installer();
    }

    /**
     * @Access can_see_index
     */
    public function start()
    {
        $render = [
            'requirements' => $this->installer->checkRequirements(),
            'can_install' => $this->installer->canInstall(),
            'phpversion' => phpversion()
        ];

        return $this->get('view')->display('Actions/Installer', 'Start', $render);
    }

    /**
     * @Access can_see_index
     */
    public function collectData()
    {
        $form = $this->get('form')->create(new InstallerType(), [
            'translator' => $this->get('translator')
        ]);

        $form->handleRequest(Request::createFromGlobals());

        if ($form->isSubmitted() === true && $form->isValid() === true) {

        }

        $render = [
            'form' => $form->createView()
        ];

        return $this->get('view')->display('Actions/Installer', 'CollectData', $render);
    }
}
