<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 21/09/15
 * Time: 09:01
 */

namespace Clearbooks\LabsApi\Group;


use Clearbooks\Labs\User\UseCase\PermissionService;

class GroupToggleModifierPermissionServiceStub implements PermissionService
{
    /**
     * @var bool
     */
    private $isAdmin;

    /**
     * GroupToggleModifierPermissionServiceStub constructor.
     * @param bool $isAdmin
     */
    public function __construct($isAdmin)
    {
        $this->isAdmin = $isAdmin;
    }


    /**
     * @param int $userIdentifier
     * @param int $groupIdentifier
     * @return bool
     */
    public function isGroupAdmin($userIdentifier, $groupIdentifier)
    {
        return $this->isAdmin;
    }
}