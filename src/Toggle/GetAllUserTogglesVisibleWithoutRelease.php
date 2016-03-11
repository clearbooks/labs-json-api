<?php
namespace Clearbooks\LabsApi\Toggle;

use Clearbooks\Dilex\Endpoint;
use Clearbooks\Labs\Toggle\UseCase\GetUserTogglesVisibleWithoutRelease;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GetAllUserTogglesVisibleWithoutRelease implements Endpoint
{
    /**
     * @var GetUserTogglesVisibleWithoutRelease
     */
    private $getUserTogglesVisibleWithoutRelease;

    /**
     * @var MarketableToggleToArrayConverter
     */
    private $marketableToggleToArrayConverter;

    /**
     * @param GetUserTogglesVisibleWithoutRelease $getUserTogglesVisibleWithoutRelease
     * @param MarketableToggleToArrayConverter $marketableToggleToArrayConverter
     */
    public function __construct( GetUserTogglesVisibleWithoutRelease $getUserTogglesVisibleWithoutRelease,
                                 MarketableToggleToArrayConverter $marketableToggleToArrayConverter )
    {
        $this->getUserTogglesVisibleWithoutRelease = $getUserTogglesVisibleWithoutRelease;
        $this->marketableToggleToArrayConverter = $marketableToggleToArrayConverter;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function execute( Request $request )
    {
        $response = $this->getUserTogglesVisibleWithoutRelease->execute();
        $responseData = $this->marketableToggleToArrayConverter->getArrayFromToggles( $response->getToggles() );
        return new JsonResponse( $responseData );
    }
}
