<?php
namespace Clearbooks\LabsApi\Toggle;

use Clearbooks\Labs\Toggle\Entity\MarketableToggle;
use Clearbooks\Labs\Toggle\Object\GetTogglesVisibleWithoutReleaseResponse;
use Clearbooks\Labs\Toggle\UseCase\GetGroupTogglesVisibleWithoutRelease;
use Clearbooks\Labs\Toggle\UseCase\GetUserTogglesVisibleWithoutRelease;

class GetVisibleTogglesWithoutReleaseStub implements GetUserTogglesVisibleWithoutRelease, GetGroupTogglesVisibleWithoutRelease
{
    /**
     * @var MarketableToggle[]
     */
    private $togglesWithoutRelease = [ ];

    /**
     * @return GetTogglesVisibleWithoutReleaseResponse
     */
    public function execute()
    {
        return new GetTogglesVisibleWithoutReleaseResponse( $this->togglesWithoutRelease );
    }

    /**
     * @param MarketableToggle[] $togglesWithoutRelease
     */
    public function setTogglesWithoutRelease( $togglesWithoutRelease )
    {
        $this->togglesWithoutRelease = $togglesWithoutRelease;
    }
}
