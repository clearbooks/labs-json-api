<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 18/09/15
 * Time: 15:00
 */

namespace Clearbooks\LabsApi\Group;


use Clearbooks\Labs\User\SuccessfulToggleStatusModifierServiceSpy;
use Clearbooks\Labs\User\ToggleStatusModifier;
use Clearbooks\Labs\User\ToggleStatusModifierRequestValidator;
use Clearbooks\LabsApi\EndpointTest;
use Clearbooks\LabsApi\User\MockIdentityProvider;

class GroupToggleStatusModifierTest extends EndpointTest
{
    private function createEndpoint($userId, $groupId, $isAdmin = true)
    {
        $permissionService = new GroupToggleModifierPermissionServiceStub($isAdmin);

        $this->endpoint = new GroupToggleStatusModifier(
            new ToggleStatusModifier(
                new SuccessfulToggleStatusModifierServiceSpy(),
                new ToggleStatusModifierRequestValidator(
                    $permissionService
                )
            ),
            new MockIdentityProvider($userId, $groupId),
            new GroupToggleModifierPermissionServiceStub($isAdmin)
        );
    }

    /**
     * @test
     */
    public function givenNoToggleId_whenTogglingStatus_return400()
    {
        $this->createEndpoint(1, 1);

        $this->executeWithPostParams([GroupToggleStatusModifier::TOGGLE_STATUS => 'active']);

        $this->assert400();
    }

    /**
     * @test
     */
    public function givenNoToggleStatus_whenTogglingStatus_return400()
    {
        $this->createEndpoint(1, 1);

        $this->executeWithPostParams([GroupToggleStatusModifier::TOGGLE_ID => '1']);

        $this->assert400();
    }

    /**
     * @test
     */
    public function givenNoUserId_whenTogglingStatus_return400()
    {
        $this->createEndpoint(null, 1);

        $this->executeWithPostParams([
            GroupToggleStatusModifier::TOGGLE_ID => '1',
            GroupToggleStatusModifier::TOGGLE_STATUS => "active"
        ]);

        $this->assert400();
    }

    /**
     * @test
     */
    public function givenNoGroupId_whenTogglingStatus_return400()
    {
        $this->createEndpoint(1, null);

        $this->executeWithPostParams([
            GroupToggleStatusModifier::TOGGLE_ID => '1',
            GroupToggleStatusModifier::TOGGLE_STATUS => "active"
        ]);

        $this->assert400();
    }

    /**
     * @test
     */
    public function givenInvalidToggleStatus_whenTogglingGroupStatus_return400()
    {
        $this->createEndpoint(1,1);

        $this->executeWithPostParams([
            GroupToggleStatusModifier::TOGGLE_ID => '1',
            GroupToggleStatusModifier::TOGGLE_STATUS => "chumpMode"]);
        $this->assert400();
    }

    /**
     * @test
     */
    public function givenUserIsNotAdmin_whenTogglingGroupStatus_return403()
    {
        $this->createEndpoint(1, 1, false);

        $this->executeWithPostParams([
            GroupToggleStatusModifier::TOGGLE_ID => '1',
            GroupToggleStatusModifier::TOGGLE_STATUS => 'active']);
        $this->assertEquals(403, $this->response->getStatusCode());
    }

    /**
     * @test
     */
    public function givenCorrectInputsAndUserIsAdmin_whenTogglingGroupStatus_returnTrue()
    {
        $this->createEndpoint(1,1);

        $this->executeWithPostParams([
            GroupToggleStatusModifier::TOGGLE_ID => '1',
            GroupToggleStatusModifier::TOGGLE_STATUS => 'active']);
        $this->assertJsonResponse(['result' => true]);
    }
}
