<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 21/08/15
 * Time: 14:59
 */

namespace Clearbooks\LabsApi\Toggle;

use Clearbooks\Labs\Release\Gateway\MockReleaseGateway;
use Clearbooks\Labs\Release\Release;
use Clearbooks\Labs\Toggle\Gateway\StubGroupToggleGateway;
use Clearbooks\Labs\Toggle\GetGroupTogglesForRelease as labsGetGroupToggles;
use Clearbooks\LabsApi\EndpointTest;
use Clearbooks\LabsMysql\Toggle\Entity\Toggle;
use DateTime;

class GetGroupTogglesForReleaseTest extends EndpointTest
{
    /**
     * @var Toggle[]
     */
    private $groupToggles;

    public function setUp(): void
    {
        $this->groupToggles = [
            new Toggle('0', 'dog', '0', true, "group", "", "","","","","","","Dog title")
        ];

        $releases = [
            new Release('Cat', 'dog', 'url', new DateTime(), true)
        ];

        $this->endpoint = new GetGroupTogglesForRelease(new labsGetGroupToggles(
            new StubGroupToggleGateway($this->groupToggles),
            new MockReleaseGateway($releases)
        ));
    }

    /**
     * @test
     */
    public function givenNoReleaseID_return400()
    {
        $this->executeWithQuery([]);
        $this->assert400();
    }

    /**
     * @test
     */
    public function givenReleaseId_returnCorrectGroupToggle()
    {
        $this->executeWithQuery(['release' => 0]);
        $this->assertJsonResponse([
            [
                'id' => $this->groupToggles[0]->getId(),
                'name' => $this->groupToggles[0]->getMarketingToggleTitle(),
                'summary' => "",
                'url' => "",
                'screenshot' => "",
                'type' => "group"
            ]
        ]);
    }
}
