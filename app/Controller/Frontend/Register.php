<?php
namespace Tricolore\Controller\Frontend;

use Tricolore\Services\ServiceLocator;
use Tricolore\FormFactory\FormTypes\Frontend\RegisterType;
use Tricolore\Member\Member;
use Symfony\Component\HttpFoundation\Request;

class Register extends ServiceLocator
{
    /**
     * @Route('/register', name="register")
     */
    public function process()
    {
        $form = $this->get('form')->create(new RegisterType(), [
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
