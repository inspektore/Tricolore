<?php
namespace Tricolore\Controller\Frontend;

use Tricolore\Foundation\Application;
use Tricolore\Session\Session;
use Tricolore\Form\FormTypes\Frontend\AuthType;
use Tricolore\Controller\ControllerAbstract;
use Tricolore\Member\Member;
use Tricolore\Config\Config;
use Tricolore\Security\Csrf\CsrfToken;
use Tricolore\Exception\NoPermissionException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;

class Auth extends ControllerAbstract
{
    /**
     * @Access can_see_index
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
            $member_finder = $this->get('member.finder')->findByStrategy($form->getData()['login']);
            $validation = $this->get('member')->validate($member_finder, $form->getData()['password']);

            if ($validation === true) {
                if ($form->getData()['autologin'] === true) {
                    $this->get('cookiejar')->set('member_id', $member_finder->container()['id'], 31556926);
                    $this->get('cookiejar')->set('token', $member_finder->container()['token'], 31556926);
                }

                Session::getSession()->set('member_id', $member_finder->container()['id']);

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
     * @Access can_see_index
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
