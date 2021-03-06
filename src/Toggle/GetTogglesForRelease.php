<?php
namespace Clearbooks\LabsApi\Toggle;
use Clearbooks\Labs\Release\GetReleaseToggles;
use Clearbooks\Dilex\Endpoint;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetTogglesForRelease implements Endpoint
{
    /**
     * @var GetReleaseToggles
     */
    private $getToggles;

    /**
     * @param GetReleaseToggles $getReleaseToggles
     */
    public function __construct( GetReleaseToggles $getReleaseToggles )
    {
        $this->getToggles = $getReleaseToggles;
    }

    /**
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function execute( Request $request )
    {
        if ( !$releaseId = $request->get( 'release' ) ) {
            return new JsonResponse( 'Missing release ID', 400 );
        }

        $toggles = $this->getToggles->execute( $releaseId );
        $json = [];

        foreach ( $toggles as $r ) {
            $json[] = [
                'id' => $r->getId(),
                'name' => $r->getName(),
                'summary' => $r->getDescription(),
                'url' => $r->getUrl()
            ];
        }

        return new JsonResponse( $json );
    }
}