<?php
/**
 * Created by PhpStorm.
 * User: dan
 * Date: 27/08/15
 * Time: 09:52
 */

namespace Clearbooks\LabsApi\Toggle;


use Clearbooks\Dilex\JwtGuard\IdentityProvider;
use Clearbooks\Labs\Toggle\GetActivatedToggles;
use Clearbooks\Dilex\Endpoint;
use Clearbooks\LabsApi\User\Group;
use Clearbooks\LabsApi\User\User;
use Clearbooks\LabsMysql\Toggle\Entity\Toggle;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GetTogglesActivatedByUser implements Endpoint
{
    /**
     * @var GetActivatedToggles
     */
    private $getActivatedToggles;
    /**
     * @var IdentityProvider
     */
    private $identityProvider;

    /**
     * GetTogglesActivatedByUser constructor.
     * @param GetActivatedToggles $getActivatedToggles
     * @param IdentityProvider $identityProvider
     */
    public function __construct(GetActivatedToggles $getActivatedToggles, IdentityProvider $identityProvider)
    {
        $this->getActivatedToggles = $getActivatedToggles;
        $this->identityProvider = $identityProvider;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function execute(Request $request)
    {
        $userId = $this->identityProvider->getUserId();
        if(!isset($userId)) {
            return new JsonResponse('Missing user identifier', 400);
        }

        $activatedToggles = $this->getActivatedToggles->execute( new User( $this->identityProvider ) , new Group( $this->identityProvider ));
        $json = [];
        /**
         * @var Toggle $toggle
         */
        foreach($activatedToggles as $toggle) {
            $json[$toggle->getName()] = 1;
        }

        return new JsonResponse($json);
    }
}