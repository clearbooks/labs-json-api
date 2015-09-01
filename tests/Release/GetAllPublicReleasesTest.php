<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 20/08/15
 * Time: 15:32
 */

namespace Clearbooks\LabsApi\Release;


use Clearbooks\Labs\Release\Gateway\MockPublicReleaseGateway;
use Clearbooks\Labs\Release\GetPublicReleases;
use Clearbooks\Labs\Release\Release;
use Clearbooks\LabsApi\EndpointTest;
use DateTime;

class GetAllPublicReleasesTest extends EndpointTest
{
    private $collectionMock;

    public function createCollectionMock(array $releases)
    {


        $this->endpoint = new GetAllPublicReleases(
            new GetPublicReleases(
                $this->collectionMock = new MockPublicReleaseGateway($releases), new DateTime()
            )
        );
    }

    /**
     * @return DateTime
     */
    private function getDateTenDaysIntoFuture()
    {
        $futureDate = new DateTime();
        $futureDate->modify("+7 day");
        return $futureDate;
    }

    /**
     * @test
     */
    public function givenGatewayWithOneVisibleRelease_WhenGettingReleases_GetVisibleRelease()
    {
        $this->createCollectionMock([
            new Release('A', 'B', 'url', new DateTime('2010-01-01'), true)
        ]);

        $this->executeWithQuery([]);
        $this->assertJsonResponse(
            [[
                'id' => 'A',
                'name' => 'B',
                'releaseInfoUrl' => 'url',
                'date' => '2010-01-01'
            ]]
        );
    }

    /**
     * @test
     */
    public function givenGatewayWithTwoVisibleReleases_WhenGettingReleases_GetBothReleases()
    {
        $futureDate = $this->getDateTenDaysIntoFuture();
        $this->createCollectionMock([
            new Release('A', 'B', 'url', new DateTime('2010-01-01'), true),
            new Release('C', 'D', 'url', $futureDate, true)
        ]);

        $this->executeWithQuery([]);
        $this->assertJsonResponse(
            [
                [
                    'id' => 'A',
                    'name' => 'B',
                    'releaseInfoUrl' => 'url',
                    'date' => '2010-01-01'
                ],
                [
                    'id' => 'C',
                    'name' => 'D',
                    'releaseInfoUrl' => 'url',
                    'date' => $futureDate->format('Y-m-d')
                ],
            ]
        );
    }

    /**
     * @test
     */
    public function givenGatewayWithOneVisibleAndOneNonVisibleRelease_WhenGettingReleases_GetOnlyVisibleRelease()
    {
        $futureDate = $this->getDateTenDaysIntoFuture();
        $this->createCollectionMock([
            new Release('A', 'B', 'url', new DateTime('2010-01-01'), true),
            new Release('C', 'D', 'url', $futureDate, false)
        ]);

        $this->executeWithQuery([]);
        $this->assertJsonResponse(
            [[
                'id' => 'A',
                'name' => 'B',
                'releaseInfoUrl' => 'url',
                'date' => '2010-01-01'
            ]]
        );
    }

}
