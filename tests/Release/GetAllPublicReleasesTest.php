<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 20/08/15
 * Time: 15:32
 */

namespace Clearbooks\LabsApi\Release;


use Clearbooks\Labs\Release\Gateway\MockReleaseGateway;
use Clearbooks\Labs\Release\GetPublicRelease;
use Clearbooks\Labs\Release\Release;
use Clearbooks\LabsApi\EndpointTest;
use DateTime;

class GetAllPublicReleasesTest extends EndpointTest
{
    private $collectionMock;

    public function createCollectionMock(array $releases)
    {


        $this->endpoint = new GetAllPublicReleases(
            new GetPublicRelease(
                $this->collectionMock = new MockReleaseGateway($releases), new DateTime()
            )
        );
    }

    /**
     * @test
     */
    public function givenGatewayWithOneVisibleRelease_WhenGettingReleases_GetVisibleRelease()
    {
        $this->createCollectionMock([
            new Release('A', 'B', new DateTime('2010-01-01'), true)
        ]);

        $this->executeWithQuery([]);
        $this->assertJsonResponse(
            [[
                'name' => 'A',
                'date' => '2010-01-01',
                'releaseInfoUrl' => 'B'
            ]]
        );
    }

    /**
     * @test
     */
    public function givenGatewayWithTwoVisibleReleases_WhenGettingReleases_GetBothReleases()
    {
        $this->createCollectionMock([
            new Release('A', 'B', new DateTime('2010-01-01'), true),
            new Release('C', 'D', new DateTime('2011-01-01'), true)
        ]);

        $this->executeWithQuery([]);
        $this->assertJsonResponse(
            [
                [
                    'name' => 'A',
                    'date' => '2010-01-01',
                    'releaseInfoUrl' => 'B'
                ],
                [
                    'name' => 'C',
                    'date' => '2011-01-01',
                    'releaseInfoUrl' => 'D'
                ]
            ]
        );
    }

}
