<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 07/09/15
 * Time: 16:38
 */

namespace Clearbooks\LabsApi\User;


use Clearbooks\Labs\AutoSubscribe\UseCase\AutoSubscriber;
use Clearbooks\LabsApi\Authentication\Tokens\UserInformationProvider;
use Clearbooks\LabsApi\Framework\Endpoint;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class IsUserAutoSubscribed implements Endpoint
{
    /**
     * @var AutoSubscriber
     */
    private $autoSubscriber;
    /**
     * @var UserInformationProvider
     */
    private $userInformationProvider;

    /**
     * IsUserAutoSubscribed constructor.
     * @param AutoSubscriber $autoSubscriber
     * @param UserInformationProvider $userInformationProvider
     */
    public function __construct(AutoSubscriber $autoSubscriber, UserInformationProvider $userInformationProvider)
    {
        $this->autoSubscriber = $autoSubscriber;
        $this->userInformationProvider = $userInformationProvider;
    }

    public function execute(Request $request)
    {
        $userId = $this->userInformationProvider->getUserId();
        if(!isset($userId)) return new JsonResponse("User ID not provided.", 400);
        return new JsonResponse([$this->autoSubscriber->isUserAutoSubscribed()]);
    }
}