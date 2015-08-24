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
use Clearbooks\Labs\Toggle\Entity\GroupToggleStub;
use Clearbooks\Labs\Toggle\Gateway\StubGroupToggleGateway;
use Clearbooks\LabsApi\EndpointTest;
use DateTime;

class GetGroupTogglesForReleaseTest extends EndpointTest
{

    public function setUp()
    {
        $groupToggles = [
            new GroupToggleStub('0')
        ];

        $releases = [
            new Release('Cat', 'url', new DateTime(), true)
        ];

        $this->endpoint = new GetGroupTogglesForRelease(
            new StubGroupToggleGateway($groupToggles),
            new MockReleaseGateway($releases)
        );
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
        $this->executeWithQuery(['release' => 1]);
        //TODO: Implement this when GroupToggles are implemented
    }

}
