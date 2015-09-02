<?php
/**
 * Created by PhpStorm.
 * User: dan
 * Date: 27/08/15
 * Time: 12:15
 */

namespace Clearbooks\LabsApi\User;


use Clearbooks\Labs\User\ToggleStatusModifier\Request as ModifyToggleRequest;
use Clearbooks\Labs\User\ToggleStatusModifierResponseHandlerSpy;
use Clearbooks\Labs\User\UseCase\ToggleStatusModifier;
use Clearbooks\LabsApi\Framework\Endpoint;
use Clearbooks\LabsApi\Framework\Tokens\TokenProvider;
use Emarref\Jwt\Algorithm\AlgorithmInterface;
use Emarref\Jwt\Jwt;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserToggleStatusModifier implements Endpoint
{
    /**
     * @var UserToggleStatusModifier
     */
    private $statusModifier;
    /**
     * @var ToggleStatusModifierResponseHandlerSpy
     */
    private $toggleStatusModifierResponseHandler;

    /**
     * ToggleStatusModifier constructor.
     * @param ToggleStatusModifier $toggleStatusModifier
     * @param ToggleStatusModifierResponseHandlerSpy $toggleStatusModifierResponseHandler
     * @param AlgorithmInterface $algorithm
     */
    public function __construct(ToggleStatusModifier $toggleStatusModifier,
                                ToggleStatusModifierResponseHandlerSpy $toggleStatusModifierResponseHandler,
                                AlgorithmInterface $algorithm)
    {
        $this->statusModifier = $toggleStatusModifier;
        $this->toggleStatusModifierResponseHandler = $toggleStatusModifierResponseHandler;
        $this->algorithm = $algorithm;
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

        list($userId, $groupId) = $this->getUserAndGroupIds($request);
        $labsRequest = $this->createLabsRequest($request, $userId, $groupId);
        $this->statusModifier->execute($labsRequest, $this->toggleStatusModifierResponseHandler);
        $response = $this->toggleStatusModifierResponseHandler->getLastHandledResponse();

        if(!empty($response->getErrors())) {
            return new JsonResponse("An error occurred", 400);
        }
        return new JsonResponse([true]);
    }

    /**
     * @param Request $request
     * @param $userId
     * @param null|string $groupId
     * @return ModifyToggleRequest
     */
    private function createLabsRequest(Request $request, $userId, $groupId = null)
    {
        return new ModifyToggleRequest($request->get('toggleId'), $request->get('newStatus'), $userId, $groupId);
    }

    /**
     * @param Request $request
     * @return bool
     */
    private function requestIsNotValid(Request $request)
    {
        $toggleId = $request->get('toggleId');
        $newStatus = $request->get('newStatus');

        return(!isset($toggleId) || !isset($newStatus));
    }

    private function getUserAndGroupIds($request)
    {
        $tokenProvider = new TokenProvider($request, new Jwt(), $this->algorithm);
        return array($tokenProvider->getUserId(), $tokenProvider->getGroupId());
    }
}