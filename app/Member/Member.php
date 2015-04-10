<?php
namespace Tricolore\Member;

use Tricolore\Services\ServiceLocator;
use Tricolore\Member\LoadMember;
use Tricolore\Exception\MemberException;
use Tricolore\Security\Encoder\BCrypt;
use Tricolore\Session\Session;
use Symfony\Component\Security\Core\Util\StringUtils;
use Symfony\Component\HttpFoundation\Request;

class Member extends ServiceLocator
{
    /**
     * Member instance
     *
     * @return Tricolore\Member\Member
     */
    public static function getInstance()
    {
        return new static();
    }

    /**
     * Validate member
     * 
     * @param Tricolore\Member\LoadMember $member
     * @param string $raw_password
     * @return bool|string
     */
    public function validate(LoadMember $member, $raw_password)
    {
        if ($member->exists() === false) {
            return $this->get('translator')->trans('Account with this username or email not exists.');
        }

        $verify_password = BCrypt::hashVerify($raw_password, $member->container()['password']);

        return ($verify_password) ?: $this->get('translator')->trans('Password for this account is not valid.');
    }

    /**
     * Member is logged in
     * 
     * @codeCoverageIgnore
     * @return bool
     */
    public function isLoggedIn()
    {
        $this->checkAutologin();

        if (Session::getSession()->has('member_id') === true && Session::getSession()->get('member_id') > 0) {
            return true;
        }

        return false;
    }

    /**
     * Check for autologin
     * 
     * @codeCoverageIgnore
     * @return void
     */
    private function checkAutologin()
    {
        if (Session::getSession()->has('member_id') === false
            && $this->get('cookiejar')->get('member_id') != null 
            && $this->get('cookiejar')->get('token') != null
        ) {
            $load_member = $this->get('load_member')
                ->byId($this->get('cookiejar')->get('member_id'));

            $validation = false;

            if ($load_member->exists() === true) {
                if (StringUtils::equals($load_member->container()['token'], $this->get('cookiejar')->get('token')) === true) {
                    $validation = true;
                }
            }

            if ($validation === true) {
                Session::getSession()->set('member_id', $load_member->container()['id']);

                Session::getSession()
                    ->getFlashBag()
                    ->add('alert-info', $this->get('translator')->trans(sprintf('Welcome back, %s!', $load_member->container()['username'])));
            }
        }
    }

    /**
     * Get logged in member data
     * 
     * @codeCoverageIgnore
     * @return array
     */
    public function getData()
    {
        if ($this->isLoggedIn() === false) {
            return [];
        }

        return $this->get('load_member')
            ->byId(Session::getSession()->get('member_id'))
            ->container();
    }

    /**
     * Kill member session
     * 
     * @codeCoverageIgnore
     * @return void
     */
    public function killCurrentSession()
    {
        Session::getSession()->remove('member_id');
        $this->get('cookiejar')->destroy('member_id');
        $this->get('cookiejar')->destroy('token');
    }
    

    /**
     * Create member
     * 
     * @param string $role
     * @param int $group_id
     * @param string $email
     * @param string $username
     * @param string $raw_password
     * @throws \Exception
     * @return bool|string
     */
    public function create($role, $group_id, $email, $username, $raw_password)
    {
        // Temporary
        return true;
    }
}
