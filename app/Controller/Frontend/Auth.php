<?php
namespace Tricolore\Controller\Frontend;

use Tricolore\Foundation\Application;
use Tricolore\Session\Session;
use Tricolore\FormFactory\FormTypes\Frontend\AuthType;
use Tricolore\Services\ServiceLocator;
use Tricolore\Member\Member;
use Tricolore\Member\LoadMember;
use Tricolore\Config\Config;
use Tricolore\Security\Csrf\CsrfToken;
use Tricolore\Exception\NoPermissionException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;

class Auth extends ServiceLocator
{
    /**
     * @Route('/auth', name="auth")
     */
    public function login()
    {
        if (Member::getInstance()->isLoggedIn() === true) {
            Session::getSession()
                ->getFlashBag()
                ->add('alert-warning alert-important', $this->get('translator')->trans('You have an existing session. Please logout first.'));

            redirect(Config::getParameter('base.full_url'));
        }

        $form = $this->get('form')->create(new AuthType(), [
            'translator' => $this->get('translator')
        ]);

        $form->handleRequest(Request::createFromGlobals());

        if ($form->isSubmitted() === true && $form->isValid() === true) {
            if (filter_var($form->getData()['login'], FILTER_VALIDATE_EMAIL)) {
                $load_member = $this->get('load_member')
                    ->byEmail($form->getData()['login']);
            } else {
                $load_member = $this->get('load_member')
                    ->byUsername($form->getData()['login']);
            }

            $validation = $this->get('member')->validate($load_member, $form->getData()['password']);

            if ($validation === true) {
                if ($form->getData()['autologin'] === true) {
                    $this->get('cookiejar')->set('member_id', $load_member->container()['id'], 31556926);
                    $this->get('cookiejar')->set('token', $load_member->container()['token'], 31556926);
                }

                Session::getSession()->set('member_id', $load_member->container()['id']);

                Session::getSession()
                    ->getFlashBag()
                    ->add('alert-success', $this->get('translator')->trans('You are successfully logged in.'));

                redirect(Config::getParameter('base.full_url'));
            }

            $form->addError(new FormError($validation));
        }

        $render = [
            'form' => $form->createView()
        ];

        return $this->get('view')->display('Actions/Frontend', 'Auth', $render);
    }

    /**
     * @Route('/auth/logout', name="auth_logout")
     */
    public function logout()
    {
        if (CsrfToken::isValid('logout') === false) {
            throw new NoPermissionException('CSRF token is invalid.');
        }

        $this->get('member')->killCurrentSession();

        Session::getSession()
            ->getFlashBag()
            ->add('alert-info', $this->get('translator')->trans('You are successfully logged out.'));

        redirect(Config::getParameter('base.full_url'));
    }
}
