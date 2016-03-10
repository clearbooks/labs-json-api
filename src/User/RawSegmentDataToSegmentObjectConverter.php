<?php
namespace Clearbooks\LabsApi\User;

class RawSegmentDataToSegmentObjectConverter
{
    const DEFAULT_PRIORITY = 0;
    const DEFAULT_IS_LOCKED = false;

    /**
     * @param array $rawSegments
     * @return Segment[]
     */
    public function getSegmentObjects( array $rawSegments )
    {
        $segmentObjects = [ ];
        foreach ( $rawSegments as $rawSegment ) {
            if ( !isset( $rawSegment["segmentId"] ) ) {
                continue;
            }

            $segmentObjects[] = new Segment(
                    $rawSegment["segmentId"],
                    isset( $rawSegment["priority"] ) ? $rawSegment["priority"] : self::DEFAULT_PRIORITY,
                    isset( $rawSegment["isLocked"] ) ? $rawSegment["isLocked"] : self::DEFAULT_IS_LOCKED
            );
        }

        return $segmentObjects;
    }
}
