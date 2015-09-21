<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 21/09/15
 * Time: 10:44
 */

namespace Clearbooks\LabsApi\Group;


use Clearbooks\LabsApi\User\MockIdentityProvider;

class GroupToggleModifierPermissionServiceTest extends \PHPUnit_Framework_TestCase
{
    const USER_ID = 1;
    const GROUP_ID = 2;
    /**
     * @var GroupToggleModifierPermissionService
     */
    private $permissionService;

    public function setUp()
    {
        $this->permissionService = new GroupToggleModifierPermissionService(new MockIdentityProvider(self::USER_ID, self::GROUP_ID, true));
    }

    /**
     * @test
     */
    public function whenIsAdminIsSet_whenGettingAdmin_returnTrue()
    {
        $this->assertTrue($this->permissionService->isGroupAdmin(self::USER_ID, self::GROUP_ID));
    }
}
