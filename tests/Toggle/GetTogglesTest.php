<?php
namespace Clearbooks\LabsApi\Toggle;
use Clearbooks\Labs\Release\Gateway\ReleaseToggleCollectionMock;
use Clearbooks\Labs\Release\GetReleaseToggles;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class GetTogglesTest
 * @package Clearbooks\LabsApi\Toggle
 */
class GetTogglesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var GetToggles
     */
    private $endpoint;

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
        $response = $this->endpoint->execute( new Request );
        $this->assertEquals( 400, $response->getStatusCode() );
    }

    /**
     * @test
     */
    public function givenRelease_passThroughToGateway()
    {
        $this->endpoint->execute( new Request( ['release' => 1] ) );
        $this->assertEquals( 1, $this->collectionMock->releaseId );
    }
}
