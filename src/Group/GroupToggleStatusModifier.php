<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 18/09/15
 * Time: 14:07
 */

namespace Clearbooks\LabsApi\Group;


use Clearbooks\Dilex\Endpoint;
use Clearbooks\Dilex\JwtGuard\IdentityProvider;
use Clearbooks\Labs\User\ToggleStatusModifier\Request as LabsRequest;
use Clearbooks\Labs\User\UseCase\PermissionService;
use Clearbooks\Labs\User\UseCase\ToggleStatusModifier;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GroupToggleStatusModifier implements Endpoint
{
    const TOGGLE_ID = "toggleId";

    const TOGGLE_STATUS = "newStatus";

    const USER_ID = "userId";

    const GROUP_ID = "groupId";

    /**
     * @var IdentityProvider
     */
    private $identityProvider;
    /**
     * @var PermissionService
     */
    private $permissionService;
    /**
     * @var ToggleStatusModifier
     */
    private $toggleStatusModifier;


    /**
     * GroupToggleStatusModifier constructor.
     * @param ToggleStatusModifier $toggleStatusModifier
     * @param IdentityProvider $identityProvider
     * @param PermissionService $permissionService
     */
    public function __construct(ToggleStatusModifier $toggleStatusModifier, IdentityProvider $identityProvider, PermissionService $permissionService)
    {
        $this->toggleStatusModifier = $toggleStatusModifier;
        $this->identityProvider = $identityProvider;
        $this->permissionService = $permissionService;
    }

    public function execute(Request $request)
    {
        $toggleId = $request->request->get(self::TOGGLE_ID);
        $toggleStatus = $request->request->get(self::TOGGLE_STATUS);
        $userId = $this->identityProvider->getUserId();
        $groupId = $this->identityProvider->getGroupId();
        if($this->requestNotValid($toggleId, $toggleStatus, $userId, $groupId)) {
            return new JsonResponse("You didn't include all the necessary information.", 400);
        }
        if(!$this->permissionService->isGroupAdmin($userId, $groupId)) {
            return new JsonResponse("You are not an admin for this group.", 403);
        }

        $labsRequest = $this->createLabsRequest($toggleId, $toggleStatus, $userId, $groupId);

        $response = $this->toggleStatusModifier->execute($labsRequest);

        if(!empty($response->getErrors())) {
            return new JsonResponse("An error occurred.", 400);
        }

        return new JsonResponse(['result' => true]);
    }

    /**
     * @param $toggleId
     * @param $toggleStatus
     * @param $userId
     * @param $groupId
     * @return bool
     * @internal param Request $request
     */
    private function requestNotValid($toggleId, $toggleStatus, $userId, $groupId)
    {
        return !isset($toggleId) || !isset($toggleStatus) || !isset($userId) || !isset($groupId);
    }

    /**
     * @param $toggleId
     * @param $toggleStatus
     * @param $userId
     * @param $groupId
     * @return LabsRequest
     */
    private function createLabsRequest($toggleId, $toggleStatus, $userId, $groupId)
    {
        return new LabsRequest($toggleId, $toggleStatus, $userId, $groupId);
    }
}