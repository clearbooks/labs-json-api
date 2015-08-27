<?php
/**
 * Created by PhpStorm.
 * User: dan
 * Date: 27/08/15
 * Time: 12:18
 */

namespace User;


use Clearbooks\Labs\User\MockPermissionService;
use Clearbooks\Labs\User\SuccessfulToggleStatusModifierServiceSpy;
use Clearbooks\Labs\User\ToggleStatusModifier;
use Clearbooks\Labs\User\ToggleStatusModifierRequestValidator;
use Clearbooks\Labs\User\ToggleStatusModifierResponseHandlerSpy;
use Clearbooks\LabsApi\EndpointTest;
use Clearbooks\LabsApi\User\UserToggleStatusModifier;

class UserToggleStatusModifierTest extends EndpointTest
{

    public function setUp()
    {
        parent::setUp();

        $this->endpoint = new UserToggleStatusModifier(
            new ToggleStatusModifier(
                new SuccessfulToggleStatusModifierServiceSpy(),
                new ToggleStatusModifierRequestValidator(
                    new MockPermissionService()
                )
            ),
            new ToggleStatusModifierResponseHandlerSpy()
        );
    }

    /**
     * @test
     */
    public function givenNoToggleId_WhenTogglingStatus_Return400()
    {
        $this->executeWithQuery(['newStatus' => "active", 'userId' => '1']);
        $this->assert400();

    }

    /**
     * @test
     */
    public function givenNoNewStatus_WhenTogglingStatus_Return400()
    {
        $this->executeWithQuery(['toggleId' => '1', 'userId' => '1']);
        $this->assert400();
    }

    /**
     * @test
     */
    public function givenNoUserId_WhenTogglingStatus_Return400()
    {
        $this->executeWithQuery(['toggleId' => '1', 'newStatus' => "active"]);
        $this->assert400();
    }

    /**
     * @test
     */
    public function givenToggleThatWillError_WhenTogglingStatus_Return400()
    {
        $this->executeWithQuery([
            'toggleId' => '1',
            'newStatus' => 'asdf',
            'userId' => '1'
        ]);
        $this->assert400();
    }

    /**
     * @test
     */
    public function givenCorrectToggleInfo_WhenTogglingStatus_CorrectlyToggleStatus()
    {
        $this->executeWithQuery(
            [
                'toggleId' => '1',
                'newStatus' => "active",
                'userId' => '1'
            ]
        );

        $this->assertJsonResponse([true]);
    }
}
