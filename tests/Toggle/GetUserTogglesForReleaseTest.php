<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 21/08/15
 * Time: 11:30
 */

namespace Clearbooks\LabsApi\Toggle;


use Clearbooks\Labs\Release\Gateway\MockReleaseGateway;
use Clearbooks\Labs\Release\Release;
use Clearbooks\Labs\Toggle\Gateway\UserToggleGatewayStub;
use Clearbooks\Labs\Toggle\GetUserTogglesForRelease as LabsGetUserTogglesForRelease;
use Clearbooks\LabsApi\EndpointTest;
use Clearbooks\LabsMysql\Toggle\Entity\Toggle;
use DateTime;

class GetUserTogglesForReleaseTest extends EndpointTest
{
    public function setUp()
    {
        $userToggles = [
            new Toggle(
                '0', 'cat', '0', true, "simple",
                "screenshot", "description", "functionality", "implementationReason",
                "location", "guideUrl", "appNotificationThing"
            ),
            new Toggle(
                '1', 'dog', '0', true, "simple",
                "screenshot", "description", "functionality", "implementationReason",
                "location", "guideUrl", "appNotificationThing"
            )
        ];

        $releaseToggles = [
            new Release('Test', 'Test', 'Url', new DateTime(), true),
        ];

        $this->endpoint = new GetUserTogglesForRelease(
            new LabsGetUserTogglesForRelease(
                new UserToggleGatewayStub($userToggles),
                new MockReleaseGateway($releaseToggles)
            )
        );
    }

    /**
     * @test
     */
    public function givenNoReleaseId_return400()
    {
        $this->executeWithQuery([]);
        $this->assert400();
    }

    /**
     * @test
     */
    public function givenReleaseIdWithTwoToggles_returnToggles()
    {
        $marketingInfo = [
            'appNotificationCopyText' => 'appNotificationThing',
            'functionalityDescription' => 'functionality',
            'implementationReason' => 'implementationReason',
            'locationDescription' => 'location',
            'toggleDescription' => 'description',
            'screenshotUrl' => 'screenshot',
            'guideUrl' => 'guideUrl'
        ];
        $this->executeWithQuery(['release' => '0']);
        $this->assertJsonResponse([
            [
                'id' => '0',
                'name' => 'cat',
                'marketingInfo' => $marketingInfo
            ],
            [
                'id' => '1',
                'name' => 'dog',
                'marketingInfo' => $marketingInfo
            ]
        ]);
    }
}
