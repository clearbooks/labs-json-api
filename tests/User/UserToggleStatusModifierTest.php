<?php
/**
 * Created by PhpStorm.
 * User: dan
 * Date: 27/08/15
 * Time: 12:18
 */

namespace Clearbooks\LabsApi\User;


use Clearbooks\Labs\User\MockPermissionService;
use Clearbooks\Labs\User\SuccessfulToggleStatusModifierServiceSpy;
use Clearbooks\Labs\User\ToggleStatusModifier;
use Clearbooks\Labs\User\ToggleStatusModifierRequestValidator;
use Clearbooks\Labs\User\ToggleStatusModifierResponseHandlerSpy;
use Clearbooks\LabsApi\EndpointTest;
use Emarref\Jwt\Algorithm\None;

class UserToggleStatusModifierTest extends EndpointTest
{

    public function setUpEndpoint($userId)
    {
        $this->endpoint = new UserToggleStatusModifier(
            new ToggleStatusModifier(
                new SuccessfulToggleStatusModifierServiceSpy(),
                new ToggleStatusModifierRequestValidator(
                    new MockPermissionService()
                )
            ),
            new ToggleStatusModifierResponseHandlerSpy(),
            new None(),
            new MockIdentityProvider($userId)
        );
    }

    /**
     * @test
     */
    public function givenNoToggleId_WhenTogglingStatus_Return400()
    {
        $this->setUpEndpoint(1);
        $this->executeWithPostParams([UserToggleStatusModifier::NEW_STATUS => "active"]);
        $this->assert400();
    }

    /**
     * @test
     */
    public function givenNoNewStatus_WhenTogglingStatus_Return400()
    {
        $this->setUpEndpoint(1);
        $this->executeWithPostParams([UserToggleStatusModifier::TOGGLE_ID => '1']);
        $this->assert400();
    }

    /**
     * @test
     */
    public function givenNoUserId_WhenTogglingStatus_Return400()
    {
        $this->setUpEndpoint(null);
        $this->executeWithPostParams([UserToggleStatusModifier::TOGGLE_ID => '1', UserToggleStatusModifier::NEW_STATUS => "active"]);
        $this->assert400();
    }

    /**
     * @test
     */
    public function givenToggleThatWillError_WhenTogglingStatus_Return400()
    {
        $this->setUpEndpoint(1);
        $this->executeWithPostParams([
            UserToggleStatusModifier::TOGGLE_ID => '1',
            UserToggleStatusModifier::NEW_STATUS => 'asdf'
        ]);
        $this->assert400();
    }

    /**
     * @test
     */
    public function givenCorrectToggleInfo_WhenTogglingStatus_CorrectlyToggleStatus()
    {
        $this->setUpEndpoint(1);
        $this->executeWithPostParams(
            [
                UserToggleStatusModifier::TOGGLE_ID => '1',
                UserToggleStatusModifier::NEW_STATUS => "active"
            ]
        );
        $this->assertJsonResponse(['result' =>true]);
    }
}
