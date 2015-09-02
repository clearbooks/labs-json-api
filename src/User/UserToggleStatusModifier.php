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
     */
    public function __construct(ToggleStatusModifier $toggleStatusModifier, ToggleStatusModifierResponseHandlerSpy $toggleStatusModifierResponseHandler)
    {
        $this->statusModifier = $toggleStatusModifier;
        $this->toggleStatusModifierResponseHandler = $toggleStatusModifierResponseHandler;
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

        $groupId = $request->get('groupId');
        $labsRequest = $this->createLabsRequest($request, $groupId);
        $this->statusModifier->execute($labsRequest, $this->toggleStatusModifierResponseHandler);
        $response = $this->toggleStatusModifierResponseHandler->getLastHandledResponse();

        if(!empty($response->getErrors())) {
            return new JsonResponse("An error occurred", 400);
        }
        return new JsonResponse([true]);
    }

    /**
     * @param Request $request
     * @param null|string $groupId
     * @return ModifyToggleRequest
     */
    private function createLabsRequest(Request $request, $groupId = null)
    {
        return new ModifyToggleRequest($request->query->get('toggleId'), $request->query->get('newStatus'), $request->query->get('userId'), $groupId);
    }

    /**
     * @param Request $request
     * @return bool
     */
    private function requestIsNotValid(Request $request)
    {
        $toggleId = $request->query->get('toggleId');
        $newStatus = $request->query->get('newStatus');
        $userId = $request->query->get('userId');

        return(!isset($toggleId) || !isset($newStatus) || !isset($userId));
    }
}