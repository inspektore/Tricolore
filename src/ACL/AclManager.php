<?php
namespace Tricolore\ACL;

use Tricolore\ACL\DataCollector\AclDataCollectorAbstract;
use Symfony\Component\Security\Core\Util\StringUtils;

class AclManager extends AclDataCollectorAbstract
{
    /**
     * Check permissions
     * 
     * @param string $permission_key
     * @return bool
     */
    public function isGranded($permission_key)
    {
        $client_role = $this->get('member')->getRole();
        $permission_role = $this->getPermission($permission_key, $client_role)['permission_role'];

        if (StringUtils::equals($client_role, $permission_role) === false) {
            return false;
        }

        if (StringUtils::equals($this->getPermission($permission_key, $client_role)['permission_value'], '0') === true) {
            return false;
        }

        return true;
    }
}
