<?php
namespace Clearbooks\LabsApi\Toggle;
use Clearbooks\Labs\Release\Gateway\BrollyReleaseToggleCollection;
use Clearbooks\Labs\Release\Gateway\ReleaseToggleCollectionMock;
use Clearbooks\Labs\Release\GetReleaseToggles;
use Clearbooks\Labs\Toggle\Entity\BrollyToggle;
use Clearbooks\LabsApi\EndpointTest;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class GetTogglesTest
 * @package Clearbooks\LabsApi\Toggle
 */
class GetTogglesTest extends EndpointTest
{
    /**
     * @var ReleaseToggleCollectionMock
     */
    private $collectionMock;

    public function setUp()
    {
        $this->endpoint = new GetToggles( new GetReleaseToggles( $this->collectionMock = new ReleaseToggleCollectionMock() ) );
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
        $this->endpoint = new GetToggles( new GetReleaseToggles( new BrollyReleaseToggleCollection ) );
        $this->executeWithQuery( ['release' => 1] );
        $this->assertJsonResponse( [
            [
                'name' => BrollyToggle::NAME
            ]
        ] );
    }
}
