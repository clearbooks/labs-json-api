<?php
namespace Clearbooks\LabsApi\Toggle;

use Clearbooks\Labs\Toggle\Entity\MarketableToggle;
use Clearbooks\Labs\Toggle\Gateway\GetAllTogglesGatewayStub;
use Clearbooks\Labs\Toggle\GetAllToggleStatus;
use Clearbooks\Labs\Toggle\NameBasedCanDefaultToggleStatusBeOverruledMock;
use Clearbooks\Labs\Toggle\NameBasedToggleCheckerMock;
use Clearbooks\LabsApi\EndpointTest;
use Clearbooks\LabsApi\User\MockIdentityProvider;
use Clearbooks\LabsApi\User\RawSegmentDataToSegmentObjectConverter;
use Clearbooks\LabsMysql\Toggle\Entity\Toggle;

class GetAllToggleStatusForUserTest extends EndpointTest
{
    /**
     * @var NameBasedToggleCheckerMock
     */
    private $nameBasedToggleCheckerMock;

    /**
     * @var NameBasedCanDefaultToggleStatusBeOverruledMock
     */
    private $nameBasedCanDefaultToggleStatusBeOverruledMock;

    /**
     * @var GetAllTogglesGatewayStub
     */
    private $getAllTogglesGatewayStub;

    /**
     * @var MockIdentityProvider
     */
    private $identityProviderMock;

    public function setUp(): void
    {
        parent::setUp();
        $this->nameBasedToggleCheckerMock = new NameBasedToggleCheckerMock();
        $this->nameBasedCanDefaultToggleStatusBeOverruledMock = new NameBasedCanDefaultToggleStatusBeOverruledMock();
        $this->getAllTogglesGatewayStub = new GetAllTogglesGatewayStub();
        $this->identityProviderMock = new MockIdentityProvider( null );

        $this->endpoint = new GetAllToggleStatusForUser(
                new GetAllToggleStatus(
                        $this->getAllTogglesGatewayStub,
                        $this->nameBasedToggleCheckerMock,
                        $this->nameBasedCanDefaultToggleStatusBeOverruledMock
                ),
                $this->identityProviderMock,
                new RawSegmentDataToSegmentObjectConverter()
        );
    }

    private function givenValidIdentity()
    {
        $this->identityProviderMock->setUserId( 1 );
        $this->identityProviderMock->setGroupId( 2 );
        $this->identityProviderMock->setSegments( [ ] );
    }

    /**
     * @test
     */
    public function GivenInvalidIdentity_Expect400()
    {
        $this->executeWithQuery( [ ] );
        $this->assertEquals( 400, $this->response->getStatusCode() );
    }

    /**
     * @test
     */
    public function GivenValidIdentity_WhenThereAreNoToggles_ExpectEmptyArray()
    {
        $this->givenValidIdentity();

        $this->executeWithQuery( [ ] );
        $this->assertJsonResponse( [ ] );
    }

    /**
     * @return array
     */
    public function getTestData()
    {
        return [
            [ [ "cats" ], [ ] ],
            [ [ "cats" ], [ "dogs" ] ],
            [ [ "cats", "dogs" ], [ ] ],
            [ [ ], [ "cats", "dogs" ] ],
            [ [ "cats" ], [ "dogs" ] ]
        ];
    }

    /**
     * @test
     * @dataProvider getTestData
     *
     * @param array $enabledToggleNames
     * @param array $lockedToggleNames
     */
    public function GivenValidIdentity_WhenThereAreToggles_ExpectProperResults( array $enabledToggleNames,
                                                                                array $lockedToggleNames )
    {
        $this->givenValidIdentity();

        /** @var MarketableToggle[] $expectedToggles */
        $expectedToggles = [
                new Toggle( '1', 'cats', '0', true, "", "", "", "", "", "", "", "", "cats" ),
                new Toggle( '2', 'dogs', '0', true, "", "", "", "", "", "", "", "", "dogs" )
        ];

        $this->getAllTogglesGatewayStub->setExpectedToggles( $expectedToggles );

        foreach ( $enabledToggleNames as $enabledToggleName ) {
            $this->nameBasedToggleCheckerMock->setToggleStatus( $enabledToggleName, true );
        }

        foreach ( $lockedToggleNames as $lockedToggleName ) {
            $this->nameBasedCanDefaultToggleStatusBeOverruledMock->setToggleCanBeOverruled( $lockedToggleName, false );
        }

        $this->executeWithQuery( [ ] );

        $expectedResponse = [
                $expectedToggles[0]->getId() => [
                        "id" => $expectedToggles[0]->getId(),
                        "active" => in_array( $expectedToggles[0]->getName(), $enabledToggleNames ) ? 1 : 0,
                        "locked" => in_array( $expectedToggles[0]->getName(), $lockedToggleNames ) ? 1 : 0
                ],
                $expectedToggles[1]->getId() => [
                        "id" => $expectedToggles[1]->getId(),
                        "active" => in_array( $expectedToggles[1]->getName(), $enabledToggleNames ) ? 1 : 0,
                        "locked" => in_array( $expectedToggles[1]->getName(), $lockedToggleNames ) ? 1 : 0
                ]
        ];

        $this->assertJsonResponse( $expectedResponse );
    }
}
