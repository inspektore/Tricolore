<?php
namespace Tricolore\Member\Finder;

use Tricolore\Services\ServiceLocator;
use Tricolore\Exception\LogicException;

class MemberFinder extends ServiceLocator
{
    /**
     * Collection
     * 
     * @var array
     */
    private $collection = [];
    
    /**
     * Search by id
     * 
     * @param int $member_id
     * @return Tricolore\Member\Finder\MemberFinder
     */
    public function byId($member_id)
    {
        $this->collection = [
            'search_by' => 'id',
            'type' => 'int',
            'value' => (int)$member_id
        ];

        return $this;
    }

    /**
     * Search by email
     * 
     * @param string $member_email
     * @return Tricolore\Finder\MemberFinder
     */
    public function byEmail($member_email)
    {
        $this->collection = [
            'search_by' => 'email',
            'type' => 'str',
            'value' => $member_email
        ];

        return $this;
    }

    /**
     * Search by username
     * 
     * @param string $member_username
     * @return Tricolore\Finder\MemberFinder
     */
    public function byUsername($member_username)
    {
        $this->collection = [
            'search_by' => 'username',
            'type' => 'str',
            'value' => $member_username
        ];

        return $this;
    }

    /**
     * Find member by email or username
     * 
     * @param string $user_input
     * @return Tricolore\Finder\MemberFinder
     */
    public function findByStrategy($user_input)
    {
        if (filter_var($user_input, FILTER_VALIDATE_EMAIL) === $user_input) {
            return $this->byEmail($user_input);
        }

        return $this->byUsername($user_input);
    }

    /**
     * Member container
     * 
     * @throws Tricolore\Exception\LogicException
     * @return array|bool
     */
    public function container()
    {
        if (is_array($this->collection) === false || !count($this->collection)) {
            throw new LogicException('The required strategy byId(), byEmail() or byUsername() is missing.');
        }

        $results = $this->get('datasource')->buildQuery('select')
            ->select('id, username, password, role, group_id, joined, email, token, ip_address')
            ->from('members')
            ->where($this->collection['search_by'] . ' = ?', [
                1 => [
                    'value' => $this->collection['value'],
                    'type' => $this->collection['type']
                ]
            ])
            ->execute();

        if (!count($results)) {
            return false;
        }

        return $results[0];
    }

    /**
     * Check if member exists
     * 
     * @return bool
     */
    public function exists()
    {
        return $this->container() === false ? false : true;
    }
    
}
