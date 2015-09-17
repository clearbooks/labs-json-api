<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 07/09/15
 * Time: 16:38
 */

namespace Clearbooks\LabsApi\User;
use Clearbooks\Dilex\JwtGuard\IdentityProvider;
use Clearbooks\Labs\AutoSubscribe\UseCase\AutoSubscriber;
use Clearbooks\Dilex\Endpoint;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class IsUserAutoSubscribed implements Endpoint
{
    /**
     * @var AutoSubscriber
     */
    private $autoSubscriber;
    /**
     * @var IdentityProvider
     */
    private $userInformationProvider;

    /**
     * IsUserAutoSubscribed constructor.
     * @param AutoSubscriber $autoSubscriber
     * @param IdentityProvider $userInformationProvider
     */
    public function __construct(AutoSubscriber $autoSubscriber, IdentityProvider $userInformationProvider)
    {
        $this->autoSubscriber = $autoSubscriber;
        $this->userInformationProvider = $userInformationProvider;
    }

    public function execute(Request $request)
    {
        $userId = $this->userInformationProvider->getUserId();
        if(!isset($userId)) return new JsonResponse("User ID not provided.", 400);
        return new JsonResponse(['autoSubscribed' => $this->autoSubscriber->isUserAutoSubscribed()]);
    }
}