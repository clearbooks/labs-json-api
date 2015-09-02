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
use Clearbooks\LabsApi\Framework\Tokens\TokenProviderInterface;
use Emarref\Jwt\Algorithm\AlgorithmInterface;
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
     * @var TokenProviderInterface
     */
    private $tokenProvider;

    /**
     * ToggleStatusModifier constructor.
     * @param ToggleStatusModifier $toggleStatusModifier
     * @param ToggleStatusModifierResponseHandlerSpy $toggleStatusModifierResponseHandler
     * @param AlgorithmInterface $algorithm
     * @param TokenProviderInterface $tokenProvider
     */
    public function __construct(ToggleStatusModifier $toggleStatusModifier,
                                ToggleStatusModifierResponseHandlerSpy $toggleStatusModifierResponseHandler,
                                AlgorithmInterface $algorithm,
                                TokenProviderInterface $tokenProvider)
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

        list($userId, $groupId) = $this->getUserAndGroupIds();
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
        $toggleId = $request->request->get('toggleId');
        $newStatus = $request->request->get('newStatus');
        return(!isset($toggleId) || !isset($newStatus));
    }

    private function getUserAndGroupIds()
    {
        return array($this->tokenProvider->getUserId(), $this->tokenProvider->getGroupId());
    }
}