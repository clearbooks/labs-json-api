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
use Clearbooks\LabsApi\EndpointTest;
use Clearbooks\LabsMysql\Toggle\Entity\Toggle;
use DateTime;
use Clearbooks\Labs\Toggle\GetGroupTogglesForRelease as labsGetGroupToggles;
class GetGroupTogglesForReleaseTest extends EndpointTest
{

    /**
     * @var Toggle[]
     */
    private $groupToggles;

    public function setUp()
    {
        $this->groupToggles = [
            new Toggle('cat', '0', true)
        ];

        $releases = [
            new Release('Cat', 'url', new DateTime(), true)
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
        $this->assertEquals(400, $this->response->getStatusCode());
    }

    /**
     * @test
     */
    public function givenReleaseId_returnCorrectGroupToggle()
    {
        $this->executeWithQuery(['release' => 0]);
        $this->assertJsonResponse([
            [
                'name' => $this->groupToggles[0]->getName()
            ]
        ]);
    }

}
