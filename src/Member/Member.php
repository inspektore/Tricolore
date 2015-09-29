<?php
namespace Tricolore\Member;

use Tricolore\Services\ServiceLocator;
use Tricolore\Member\Finder\MemberFinder;
use Tricolore\Exception\AclException;
use Tricolore\Exception\ValidationException;
use Tricolore\Security\Encoder\BCrypt;
use CrawlerDetector\Detector\CrawlerDetector;
use Symfony\Component\Security\Core\Util\StringUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Util\SecureRandom;
use Carbon\Carbon;

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
     * Validate member password
     * 
     * @param Tricolore\Member\Finder\MemberFinder $member
     * @param string $raw_password
     * @throws Tricolore\Exception\ValidationException
     * @return bool
     */
    public function validatePassword(MemberFinder $member, $raw_password)
    {
        if ($member->exists() === false) {
            throw new ValidationException($this->get('translator')->trans('Account with this username or email not exists.'));
        }

        $verify_password = BCrypt::hashVerify($raw_password, $member->container()['password']);

        if ($verify_password === true) {
            return true;
        }

        throw new ValidationException($this->get('translator')->trans('Password for this account is not valid.'));
    }

    /**
     * Get logged in member role
     * 
     * @codeCoverageIgnore
     * @return string
     */
    public function getRole()
    {
        if ($this->isLoggedIn() === false) {
            if ($this->isCrawler() === true) {
                return 'ROLE_CRAWLER';
            }

            return 'ROLE_GUEST';
        }

        $finder = $this->get('member.finder')
            ->byId($this->getCurrentLoggedInMemberId())
            ->container();

        return $finder['role'];
    }

    /**
     * Check if current client is a crawler
     * 
     * @codeCoverageIgnore
     * @return bool
     */
    public function isCrawler()
    {
        $detector = new CrawlerDetector();

        return $detector->isCrawler(Request::createFromGlobals()->headers->get('User-Agent'));
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

        if ($this->get('session')->has('member_id') === true && $this->get('session')->get('member_id') > 0) {
            return true;
        }

        return false;
    }

    /**
     * Get current logged in member id
     * 
     * @codeCoverageIgnore
     * @return int
     */
    public function getCurrentLoggedInMemberId()
    {
        return $this->get('session')->get('member_id');
    }

    /**
     * Check for autologin
     * 
     * @codeCoverageIgnore
     * @return void
     */
    private function checkAutologin()
    {
        if ($this->get('session')->has('member_id') === false
            && $this->get('cookiejar')->get('member_id') != null 
            && $this->get('cookiejar')->get('token') != null
        ) {
            $member_finder = $this->get('member.finder')
                ->byId($this->get('cookiejar')->get('member_id'));

            $validation = false;

            if ($member_finder->exists() === true) {
                if (StringUtils::equals($member_finder->container()['token'], $this->get('cookiejar')->get('token')) === true) {
                    $validation = true;
                }
            }

            if ($validation === true) {
                $this->get('session')->set('member_id', $member_finder->container()['id']);

                $this->get('session')
                    ->getFlashBag()
                    ->add('alert-info', $this->get('translator')->trans(sprintf('Welcome back, %s!', $member_finder->container()['username'])));
            }
        }
    }

    /**
     * Get logged in member data
     * 
     * @codeCoverageIgnore
     * @return array
     */
    public function currentMember()
    {
        if ($this->isLoggedIn() === false) {
            return [];
        }

        return $this->get('member.finder')
            ->byId($this->getCurrentLoggedInMemberId())
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
        $this->get('session')->remove('member_id');
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
     * @throws Tricolore\Exception\AclException
     * @return void
     */
    public function create($role, $group_id, $email, $username, $raw_password)
    {
        if ($this->get('acl.manager')->roleExists($role) === false) {
            throw new AclException(sprintf('Role "%s" not exists', $role));
        }

        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw new AclException(sprintf('Email "%s" is not valid', $email));
        }

        $generator = new SecureRandom();

        $this->get('datasource')->buildQuery('insert')
            ->into('members')
            ->values([
                'username' => $username,
                'password' => BCrypt::hash($raw_password),
                'group_id' => $group_id,
                'role' => $role,
                'joined' => Carbon::now()->timestamp,
                'email' => $email,
                'token' => BCrypt::hash(bin2hex($generator->nextBytes(25))),
                'ip_address' => Request::createFromGlobals()->getClientIp()
            ])
            ->execute();
    }
}
