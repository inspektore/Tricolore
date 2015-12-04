<?php
namespace Tricolore\Controller\Frontend;

use Tricolore\Controller\ControllerAbstract;
use Tricolore\Member\Member;
use Tricolore\Form\FormTypes\Frontend\RegisterType;
use Symfony\Component\HttpFoundation\Request;

class Register extends ControllerAbstract
{
    /**
     * @Access can_see_index
     * @NoPermissionMessage You have no permission to see this page
     */
    public function process()
    {
        $form = $this->get('form')->create(RegisterType::class, [
            'translator' => $this->get('translator')
        ]);

        $form->handleRequest(Request::createFromGlobals());

        if ($form->isSubmitted() === true && $form->isValid() === true) {
            echo 'pass';
        }

        $render = [
            'form' => $form->createView()
        ];

        return $this->get('view')->display('Actions/Frontend', 'Register', $render);
    }
}
