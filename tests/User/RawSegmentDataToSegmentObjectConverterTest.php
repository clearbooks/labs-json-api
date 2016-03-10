<?php
namespace Clearbooks\LabsApi\User;

class RawSegmentDataToSegmentObjectConverterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RawSegmentDataToSegmentObjectConverter
     */
    private $rawSegmentDataToSegmentObjectConverter;

    public function setUp()
    {
        parent::setUp();
        $this->rawSegmentDataToSegmentObjectConverter = new RawSegmentDataToSegmentObjectConverter();
    }

    /**
     * @test
     */
    public function GivenEmptyArray_ReturnsEmptyArray()
    {
        $segments = $this->rawSegmentDataToSegmentObjectConverter->getSegmentObjects( [ ] );
        $this->assertEquals( [ ], $segments );
    }

    /**
     * @test
     */
    public function GivenNoSegmentId_ReturnsEmptyArray()
    {
        $segments = $this->rawSegmentDataToSegmentObjectConverter->getSegmentObjects( [
                [ "priority" => 5, "isLocked" => false ]
        ] );
        $this->assertEquals( [ ], $segments );
    }

    /**
     * @test
     */
    public function GivenSegmentIdOnly_ReturnsSegmentObjectWithDefaultData()
    {
        $segmentId = 1;
        $segments = $this->rawSegmentDataToSegmentObjectConverter->getSegmentObjects( [
                [ "segmentId" => $segmentId ]
        ] );

        $this->assertEquals(
                [
                        new Segment(
                                $segmentId,
                                RawSegmentDataToSegmentObjectConverter::DEFAULT_PRIORITY,
                                RawSegmentDataToSegmentObjectConverter::DEFAULT_IS_LOCKED
                        )
                ],
                $segments
        );
    }

    /**
     * @test
     */
    public function GivenAllAttributesAreSet_ReturnsSegmentObjectWithProperData()
    {
        $segmentId = 1;
        $priority = 10;
        $isLocked = false;
        $segments = $this->rawSegmentDataToSegmentObjectConverter->getSegmentObjects( [
                [ "segmentId" => $segmentId, "priority" => $priority, "isLocked" => $isLocked ]
        ] );

        $this->assertEquals(
                [
                        new Segment(
                                $segmentId,
                                $priority,
                                $isLocked
                        )
                ],
                $segments
        );
    }

    /**
     * @test
     */
    public function GivenMultipleSegments_ReturnsSegmentObjectsWithProperData()
    {
        $rawSegments = [
                [ "segmentId" => 1, "priority" => 10, "isLocked" => false ],
                [ "segmentId" => 2, "priority" => 5, "isLocked" => true ]
        ];
        $segments = $this->rawSegmentDataToSegmentObjectConverter->getSegmentObjects( $rawSegments );

        $this->assertEquals(
                [
                        new Segment(
                                $rawSegments[0]["segmentId"],
                                $rawSegments[0]["priority"],
                                $rawSegments[0]["isLocked"]
                        ),
                        new Segment(
                                $rawSegments[1]["segmentId"],
                                $rawSegments[1]["priority"],
                                $rawSegments[1]["isLocked"]
                        )
                ],
                $segments
        );
    }
}
