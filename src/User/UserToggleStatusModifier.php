<?php
/**
 * Created by PhpStorm.
 * User: dan
 * Date: 27/08/15
 * Time: 12:15
 */

namespace Clearbooks\LabsApi\User;


use Clearbooks\Labs\User\ToggleStatusModifier\Request as ModifyToggleRequest;
use Clearbooks\Labs\User\ToggleStatusModifier\Response;
use Clearbooks\Labs\User\ToggleStatusModifierResponseHandlerSpy;
use Clearbooks\Labs\User\UseCase\ToggleStatusModifier;
use Clearbooks\LabsApi\Framework\Endpoint;
use Clearbooks\LabsApi\Authentication\Tokens\UserInformationProvider;
use Emarref\Jwt\Algorithm\AlgorithmInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserToggleStatusModifier implements Endpoint
{
    const TOGGLE_ID = 'toggleId';
    const NEW_STATUS = 'newStatus';
    const USER_ID = 'userId';
    /**
     * @var UserToggleStatusModifier
     */
    private $statusModifier;
    /**
     * @var ToggleStatusModifierResponseHandlerSpy
     */
    private $toggleStatusModifierResponseHandler;
    /**
     * @var UserInformationProvider
     */
    private $tokenProvider;

    /**
     * ToggleStatusModifier constructor.
     * @param ToggleStatusModifier $toggleStatusModifier
     * @param ToggleStatusModifierResponseHandlerSpy $toggleStatusModifierResponseHandler
     * @param AlgorithmInterface $algorithm
     * @param UserInformationProvider $tokenProvider
     */
    public function __construct(ToggleStatusModifier $toggleStatusModifier,
                                ToggleStatusModifierResponseHandlerSpy $toggleStatusModifierResponseHandler,
                                AlgorithmInterface $algorithm,
                                UserInformationProvider $tokenProvider)
    {
        $this->statusModifier = $toggleStatusModifier;
        $this->toggleStatusModifierResponseHandler = $toggleStatusModifierResponseHandler;
        $this->algorithm = $algorithm;
        $this->tokenProvider = $tokenProvider;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function execute(Request $request)
    {
        if($this->requestIsNotValid($request)) {
            return new JsonResponse("You didn't include all the necessary information", 400);
        }

        $userId = $this->getUserId();
        $labsRequest = $this->createLabsRequest($request, $userId);
        /** @var Response $response */
        $response = $this->statusModifier->execute($labsRequest, $this->toggleStatusModifierResponseHandler);

        if(!empty($response->getErrors())) {
            return new JsonResponse("An error occurred", 400);
        }
        return new JsonResponse([true]);
    }

    /**
     * @param Request $request
     * @param $userId
     * @return ModifyToggleRequest
     */
    private function createLabsRequest(Request $request, $userId)
    {
        return new ModifyToggleRequest($request->request->get( self::TOGGLE_ID ),
                                       $request->request->get( self::NEW_STATUS ),
                                       $userId);
    }

    /**
     * @param Request $request
     * @return bool
     */
    private function requestIsNotValid(Request $request)
    {
        $toggleId = $request->request->get( self::TOGGLE_ID );
        $newStatus = $request->request->get( self::NEW_STATUS );
        $userId = $this->getUserId();
        return(!isset($toggleId) || !isset($newStatus) || !isset($userId));
    }

    private function getUserId()
    {
        return $this->tokenProvider->getUserId();
    }
}