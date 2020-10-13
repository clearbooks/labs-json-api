<?php
namespace Clearbooks\LabsApi\Toggle;

use Clearbooks\LabsApi\EndpointTest;
use Clearbooks\LabsMysql\Toggle\Entity\Toggle;
use Clearbooks\Labs\Db\Table\Toggle as ToggleTable;

class GetAllGroupTogglesVisibleWithoutReleaseTest extends EndpointTest
{
    /**
     * @var MarketableToggleToArrayConverter
     */
    private $marketableToggleToArrayConverter;

    /**
     * @var GetVisibleTogglesWithoutReleaseStub
     */
    private $getVisibleTogglesWithoutRelease;

    public function setUp(): void
    {
        parent::setUp();
        $this->marketableToggleToArrayConverter = new MarketableToggleToArrayConverter();
        $this->getVisibleTogglesWithoutRelease = new GetVisibleTogglesWithoutReleaseStub();
    }

    /**
     * @test
     */
    public function WhenExecuting_GetVisibleTogglesWithoutReleaseIsCalled()
    {
        $getVisibleTogglesWithoutReleaseSpy = new GetVisibleTogglesWithoutReleaseSpy();
        $this->endpoint = new GetAllGroupTogglesVisibleWithoutRelease( $getVisibleTogglesWithoutReleaseSpy, $this->marketableToggleToArrayConverter );

        $this->executeWithQuery( [] );
        $this->assertTrue( $getVisibleTogglesWithoutReleaseSpy->wasCalled() );
    }

    /**
     * @test
     */
    public function GivenNoToggles_ExpectEmptyArray()
    {
        $this->endpoint = new GetAllGroupTogglesVisibleWithoutRelease( $this->getVisibleTogglesWithoutRelease, $this->marketableToggleToArrayConverter );
        $this->executeWithQuery( [] );
        $this->assertJsonResponse( [ ] );
    }

    /**
     * @test
     */
    public function GivenSomeToggles_ExpectTogglesReturned()
    {
        $toggles = [
                new Toggle( "1", "Test toggle 1", null, true, ToggleTable::TYPE_GROUP ),
                new Toggle( "1", "Test toggle 1", null, true, ToggleTable::TYPE_GROUP ),
        ];

        $this->getVisibleTogglesWithoutRelease->setTogglesWithoutRelease( $toggles );
        $this->endpoint = new GetAllGroupTogglesVisibleWithoutRelease( $this->getVisibleTogglesWithoutRelease, $this->marketableToggleToArrayConverter );

        $this->executeWithQuery( [] );
        $this->assertJsonResponse( $this->marketableToggleToArrayConverter->getArrayFromToggles( $toggles ) );
    }
}
