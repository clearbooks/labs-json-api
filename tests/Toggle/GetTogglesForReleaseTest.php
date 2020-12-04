<?php
namespace Clearbooks\LabsApi\Toggle;

use Clearbooks\Labs\Release\Gateway\BrollyReleaseToggleCollection;
use Clearbooks\Labs\Release\Gateway\ReleaseToggleCollectionMock;
use Clearbooks\Labs\Release\GetReleaseToggles;
use Clearbooks\Labs\Toggle\Entity\Brolly;
use Clearbooks\LabsApi\EndpointTest;

/**
 * Class GetTogglesTest
 * @package Clearbooks\LabsApi\Toggle
 */
class GetTogglesForReleaseTest extends EndpointTest
{
    /**
     * @var ReleaseToggleCollectionMock
     */
    private $collectionMock;

    public function setUp(): void
    {
        $this->endpoint = new GetTogglesForRelease( new GetReleaseToggles( $this->collectionMock = new ReleaseToggleCollectionMock() ) );
    }

    /**
     * @test
     */
    public function givenNoRelease_return400()
    {
        $this->executeWithQuery( [] );
        $this->assert400();
    }

    /**
     * @test
     */
    public function givenRelease_passThroughToGateway()
    {
        $this->executeWithQuery( ['release' => 1] );
        $this->assertEquals( 1, $this->collectionMock->releaseId );
    }

    /**
     * @test
     */
    public function givenGatewayYieldingToggles_returnNameInJson()
    {
        $this->endpoint = new GetTogglesForRelease( new GetReleaseToggles( new BrollyReleaseToggleCollection ) );
        $this->executeWithQuery( ['release' => 1] );
        $brolly = new Brolly;

        $this->assertJsonResponse( [
            [
                'id' => $brolly->getId(),
                'name' => $brolly->getName(),
                'summary' => $brolly->getDescriptionOfToggle(),
                'url' => $brolly->getGuideUrl()
            ]
        ] );
    }
}
