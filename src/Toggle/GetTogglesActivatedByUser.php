<?php
/**
 * Created by PhpStorm.
 * User: dan
 * Date: 27/08/15
 * Time: 09:52
 */

namespace Clearbooks\LabsApi\Toggle;


use Clearbooks\Labs\Toggle\GetActivatedToggles;
use Clearbooks\LabsApi\Authentication\Tokens\UserInformationProvider;
use Clearbooks\LabsApi\Framework\Endpoint;
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
     * @var UserInformationProvider
     */
    private $tokenProvider;

    /**
     * GetTogglesActivatedByUser constructor.
     * @param GetActivatedToggles $getActivatedToggles
     * @param UserInformationProvider $tokenProvider
     */
    public function __construct(GetActivatedToggles $getActivatedToggles, UserInformationProvider $tokenProvider)
    {
        $this->getActivatedToggles = $getActivatedToggles;
        $this->tokenProvider = $tokenProvider;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function execute(Request $request)
    {
        $userId = $this->tokenProvider->getUserId();
        if(!isset($userId)) {
            return new JsonResponse('Missing user identifier', 400);
        }

        $activatedToggles = $this->getActivatedToggles->execute($userId);
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