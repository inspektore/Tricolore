<?php
namespace Tricolore\Controller\Installer;

use Tricolore\Controller\ControllerAbstract;
use Tricolore\Installer\Installer;

class Install extends ControllerAbstract
{
    /**
     * @Access can_see_index
     */
    public function start()
    {
        $installer = new Installer();

        $render = [
            'requirements' => $installer->checkRequirements(),
            'can_install' => $installer->canInstall()
        ];

        return $this->get('view')->display('Actions/Installer', 'Installer', $render);
    }
}
