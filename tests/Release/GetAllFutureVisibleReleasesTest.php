<?php
/**
 * Created by PhpStorm.
 * User: dan
 * Date: 28/12/15
 * Time: 13:29
 */

namespace Clearbooks\LabsApi\Release;


use Clearbooks\Labs\Release\Gateway\MockReleaseGateway;
use Clearbooks\Labs\Release\Release;
use Clearbooks\LabsApi\EndpointTest;

class GetAllFutureVisibleReleasesTest extends EndpointTest
{
    private $releases;

    const RELEASE_ID = "1";

    const RELEASE_NAME = "name";

    const RELEASE_URL = "url";

    const RELEASE_DATE = "2015-01-01";

    const RELEASE_VISIBLE = true;

    protected function setUp()
    {
        parent::setUp();

        $this->releases = [new Release(
            self::RELEASE_ID, self::RELEASE_NAME, self::RELEASE_URL,
            new \DateTime(self::RELEASE_DATE), self::RELEASE_VISIBLE
        )];

        $this->endpoint = new GetAllFutureVisibleReleases(
            new MockReleaseGateway($this->releases)
        );
    }


    /**
     * @test
     */
    public function givenReleases_whenGettingAllFutureVisibleReleases_returnArrayOfReleases()
    {
        $this->executeWithQuery([]);

        $this->assertJsonResponse(
            [[
                'id' => self::RELEASE_ID,
                'date' => self::RELEASE_DATE
            ]]
        );
    }


}
