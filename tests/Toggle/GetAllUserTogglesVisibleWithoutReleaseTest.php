<?php
namespace Clearbooks\LabsApi\Toggle;

use Clearbooks\LabsApi\EndpointTest;
use Clearbooks\LabsMysql\Toggle\Entity\Toggle;
use Clearbooks\Labs\Db\Table\Toggle as ToggleTable;

class GetAllUserTogglesVisibleWithoutReleaseTest extends EndpointTest
{
    /**
     * @var MarketableToggleToArrayConverter
     */
    private $marketableToggleToArrayConverter;

    /**
     * @var GetVisibleTogglesWithoutReleaseStub
     */
    private $getVisibleTogglesWithoutRelease;

    public function setUp()
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
        $this->endpoint = new GetAllUserTogglesVisibleWithoutRelease( $getVisibleTogglesWithoutReleaseSpy, $this->marketableToggleToArrayConverter );

        $this->executeWithQuery( [] );
        $this->assertTrue( $getVisibleTogglesWithoutReleaseSpy->wasCalled() );
    }

    /**
     * @test
     */
    public function GivenNoToggles_ExpectEmptyArray()
    {
        $this->endpoint = new GetAllUserTogglesVisibleWithoutRelease( $this->getVisibleTogglesWithoutRelease, $this->marketableToggleToArrayConverter );
        $this->executeWithQuery( [] );
        $this->assertJsonResponse( [ ] );
    }

    /**
     * @test
     */
    public function GivenSomeToggles_ExpectTogglesReturned()
    {
        $toggles = [
                new Toggle( "1", "Test toggle 1", null, true, ToggleTable::TYPE_SIMPLE ),
                new Toggle( "1", "Test toggle 1", null, true, ToggleTable::TYPE_SIMPLE ),
        ];

        $this->getVisibleTogglesWithoutRelease->setTogglesWithoutRelease( $toggles );
        $this->endpoint = new GetAllUserTogglesVisibleWithoutRelease( $this->getVisibleTogglesWithoutRelease, $this->marketableToggleToArrayConverter );

        $this->executeWithQuery( [] );
        $this->assertJsonResponse( $this->marketableToggleToArrayConverter->getArrayFromToggles( $toggles ) );
    }
}
