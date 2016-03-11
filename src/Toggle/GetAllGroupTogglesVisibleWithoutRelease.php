<?php
namespace Clearbooks\LabsApi\Toggle;

use Clearbooks\Dilex\Endpoint;
use Clearbooks\Labs\Toggle\UseCase\GetGroupTogglesVisibleWithoutRelease;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GetAllGroupTogglesVisibleWithoutRelease implements Endpoint
{
    /**
     * @var GetGroupTogglesVisibleWithoutRelease
     */
    private $getGroupTogglesVisibleWithoutRelease;

    /**
     * @var MarketableToggleToArrayConverter
     */
    private $marketableToggleToArrayConverter;

    /**
     * @param GetGroupTogglesVisibleWithoutRelease $getGroupTogglesVisibleWithoutRelease
     * @param MarketableToggleToArrayConverter $marketableToggleToArrayConverter
     */
    public function __construct( GetGroupTogglesVisibleWithoutRelease $getGroupTogglesVisibleWithoutRelease,
                                 MarketableToggleToArrayConverter $marketableToggleToArrayConverter )
    {
        $this->getGroupTogglesVisibleWithoutRelease = $getGroupTogglesVisibleWithoutRelease;
        $this->marketableToggleToArrayConverter = $marketableToggleToArrayConverter;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function execute( Request $request )
    {
        $response = $this->getGroupTogglesVisibleWithoutRelease->execute();
        $responseData = $this->marketableToggleToArrayConverter->getArrayFromToggles( $response->getToggles() );
        return new JsonResponse( $responseData );
    }
}
