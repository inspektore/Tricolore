<?php
namespace Tricolore\Controller\Frontend;

use Tricolore\Member\Auth\Auth as AuthService;
use Tricolore\Form\FormTypes\Frontend\AuthType;
use Tricolore\Controller\ControllerAbstract;
use Tricolore\Config\Config;
use Tricolore\Security\Csrf\CsrfToken;
use Tricolore\Exception\NoPermissionException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;

class Auth extends ControllerAbstract
{
    /**
     * @Access can_see_index
     * @NoPermissionMessage You have no permission to see this page
     */
    public function login()
    {
        $form = $this->get('form')->create(new AuthType(), [
            'translator' => $this->get('translator')
        ]);

        $form->handleRequest(Request::createFromGlobals());

        if ($form->isSubmitted() === true && $form->isValid() === true) {
            $auth = new AuthService();
            $auth->loginAttempt($form->getData()['login'], $form->getData()['password'], $form->getData()['autologin']);

            $form->addError(new FormError($auth->validationError()));
        }

        $render = [
            'form' => $form->createView()
        ];

        return $this->get('view')->display('Actions/Frontend', 'Auth', $render);
    }

    /**
     * @Access can_see_index
     */
    public function logout()
    {
        if (CsrfToken::isValid('logout') === false) {
            throw new NoPermissionException('Request authorization is invalid.');
        }

        $this->get('member')->killCurrentSession();

        $this->get('session')
            ->getFlashBag()
            ->add('alert-info', $this->get('translator')->trans('You are successfully logged out.'));

        redirect(Config::getParameter('base.full_url'));
    }
}
