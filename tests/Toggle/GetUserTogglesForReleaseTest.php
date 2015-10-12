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
                "location", "guideUrl", "appNotificationThing", "animal noise"
            ),
            new Toggle(
                '1', 'dog', '0', true, "simple",
                "screenshot", "description", "functionality", "implementationReason",
                "location", "guideUrl", "appNotificationThing", "animal noise"
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
            'toggleDescription' => 'description',
            'guideUrl' => 'guideUrl',
            'toggleTitle' => 'animal noise'
        ];
        $this->executeWithQuery(['release' => '0']);
        $this->assertJsonResponse([
            [
                'id' => '0',
                'name' => $marketingInfo['toggleTitle'],
                'summary' => $marketingInfo['toggleDescription'],
                'url' => $marketingInfo['guideUrl'],
                'screenshot' => 'screenshot',
                'type' => "simple"
            ],
            [
                'id' => '1',
                'name' => $marketingInfo['toggleTitle'],
                'summary' => $marketingInfo['toggleDescription'],
                'url' => $marketingInfo['guideUrl'],
                'screenshot' => 'screenshot',
                'type' => "simple"
            ]
        ]);
    }
}
