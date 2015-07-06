<?php
namespace Tricolore\ACL\DataCollector;

use Tricolore\Services\ServiceLocator;

abstract class AclDataCollectorAbstract extends ServiceLocator
{
    /**
     * Get permission
     * 
     * @param string $permission_key
     * @param string $client_role
     * @return string
     */
    public function getPermission($permission_key, $client_role)
    {
        return $this->get('datasource')->buildQuery('select')
            ->select('permission_key, permission_value, permission_role')
            ->from('acl_permissions')
            ->where('permission_key = ? AND permission_role = ?', [
                1 => [
                    'value' => $permission_key
                ],

                2 => [
                    'value' => $client_role
                ]
            ])
            ->maxResults(1)
            ->execute()[0];
    }
}
