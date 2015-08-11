<?php
use Clearbooks\Labs\Release\Gateway\DummyReleaseGateway;
use Clearbooks\Labs\Release\GetPublicRelease;
use Clearbooks\LabsApi\Release\GetRelease;

class GetReleaseTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var GetRelease
     */
    private $ctrl;

    public function setUp()
    {
        $this->ctrl = new GetRelease( new GetPublicRelease( new DummyReleaseGateway, new DateTime ) );
    }

    private function get()
    {
        return json_decode( $this->ctrl->execute()->getContent() );
    }

    /**
     * @test
     */
    public function givenNoReleases_outputEmptyArray()
    {
        $this->assertEquals( [], $this->get() );
    }
}
