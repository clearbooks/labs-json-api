<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 18/09/15
 * Time: 15:55
 */

namespace Clearbooks\LabsApi\Group;


use Clearbooks\Dilex\JwtGuard\IdentityProvider;
use Clearbooks\Labs\User\UseCase\PermissionService;

class GroupToggleModifierPermissionService implements PermissionService
{
    /**
     * @var IdentityProvider
     */
    private $identityProvider;

    /**
     * GroupToggleModifierPermissionService constructor.
     * @param IdentityProvider $identityProvider
     */
    public function __construct(IdentityProvider $identityProvider)
    {

        $this->identityProvider = $identityProvider;
    }


    /**
     * @param int $userIdentifier
     * @param int $groupIdentifier
     * @return bool
     */
    public function isGroupAdmin($userIdentifier, $groupIdentifier)
    {
        return $this->identityProvider->getIsAdmin();
    }
}