<?php
namespace Tricolore\Member;

use Tricolore\Services\ServiceLocator;

class LoadMember extends ServiceLocator
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
     * @return Tricolore\Member\LoadMember
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
     * @return Tricolore\Member\LoadMember
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
     * @return Tricolore\Member\LoadMember
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
     * Member container
     * 
     * @throws Tricolore\Exception\MemberException
     * @return array|bool
     */
    public function container()
    {
        $results = [];
    
        if (is_array($this->collection)) {
            $results = $this->get('datasource')->buildQuery('select')
                ->select('id, username, password, group_id, joined, email, token, ip_address')
                ->from('members')
                ->where($this->collection['search_by'] . ' = ?', [
                    1 => [
                        'value' => $this->collection['value'],
                        'type' => $this->collection['type']
                    ]
                ])
            ->execute();
        }

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
