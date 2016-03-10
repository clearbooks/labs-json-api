<?php
namespace Clearbooks\LabsApi\Toggle;

use Clearbooks\Dilex\JwtGuard\IdentityProvider;
use Clearbooks\Dilex\Endpoint;
use Clearbooks\Labs\Toggle\Object\GetAllToggleStatusRequest;
use Clearbooks\Labs\Toggle\UseCase\GetAllToggleStatus;
use Clearbooks\LabsApi\User\Group;
use Clearbooks\LabsApi\User\RawSegmentDataToSegmentObjectConverter;
use Clearbooks\LabsApi\User\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GetAllToggleStatusForUser implements Endpoint
{

    /**
     * @var GetAllToggleStatus
     */
    private $getAllToggleStatus;

    /**
     * @var IdentityProvider
     */
    private $identityProvider;

    /**
     * @var RawSegmentDataToSegmentObjectConverter
     */
    private $rawSegmentDataToSegmentObjectConverter;

    /**
     * @param GetAllToggleStatus $getAllToggleStatus
     * @param IdentityProvider $identityProvider
     * @param RawSegmentDataToSegmentObjectConverter $rawSegmentDataToSegmentObjectConverter
     */
    public function __construct( GetAllToggleStatus $getAllToggleStatus, IdentityProvider $identityProvider,
                                 RawSegmentDataToSegmentObjectConverter $rawSegmentDataToSegmentObjectConverter )
    {
        $this->getAllToggleStatus = $getAllToggleStatus;
        $this->identityProvider = $identityProvider;
        $this->rawSegmentDataToSegmentObjectConverter = $rawSegmentDataToSegmentObjectConverter;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function execute( Request $request )
    {
        $userId = $this->identityProvider->getUserId();
        if ( !isset( $userId ) ) {
            return new JsonResponse( "Missing user identifier", 400 );
        }

        $segments = $this->rawSegmentDataToSegmentObjectConverter->getSegmentObjects( $this->identityProvider->getSegments() );
        $request = new GetAllToggleStatusRequest( new User( $this->identityProvider ), new Group( $this->identityProvider ), $segments );
        $toggleStatuses = $this->getAllToggleStatus->execute( $request );

        $returnData = [ ];
        foreach ( $toggleStatuses as $toggleStatus ) {
            $returnData[$toggleStatus->getId()] = [
                    "id"     => $toggleStatus->getId(),
                    "active" => $toggleStatus->isActive() ? 1 : 0,
                    "locked" => $toggleStatus->isLocked() ? 1 : 0
            ];
        }

        return new JsonResponse( $returnData );
    }
}
