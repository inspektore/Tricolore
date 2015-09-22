<?php
namespace Tricolore\Member\Auth;

use Tricolore\Services\ServiceLocator;
use Tricolore\Config\Config;

class Auth extends ServiceLocator
{
    /**
     * Login attempt
     * 
     * @param string $login
     * @param string $password
     * @param bool $autologin
     * @return void
     */
    public function loginAttempt($login, $password, $autologin = false)
    {
        if ($this->get('member')->isLoggedIn() === true) {
            $this->get('session')
                ->getFlashBag()
                ->add('alert-warning alert-important', $this->get('translator')->trans('You have an existing session. Please logout first.'));

            redirect(Config::getParameter('base.full_url'));
        }

        $finder = $this->get('member.finder')->findByStrategy($login);

        if ($this->get('member')->validatePassword($finder, $password) === true) {
            if ($autologin === true) {
                $this->setAutologinCookies($finder->container()['id'], $finder->container()['token']);
            }

            $this->get('session')->set('member_id', $finder->container()['id']);

            $this->get('session')
                ->getFlashBag()
                ->add('alert-success', $this->get('translator')->trans('You are successfully logged in.'));

            redirect(Config::getParameter('base.full_url'));
        }
    }

    /**
     * Set autologin cookies
     * 
     * @param int $id
     * @param string $token
     * @return void
     */
    private function setAutologinCookies($id, $token)
    {
        $this->get('cookiejar')->set('member_id', $id, 31556926);
        $this->get('cookiejar')->set('token', $token, 31556926);
    }
}
