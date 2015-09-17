<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 07/09/15
 * Time: 12:38
 */

namespace Clearbooks\LabsApi\User;

use Clearbooks\Dilex\JwtGuard\IdentityProvider;
use Clearbooks\Labs\AutoSubscribe\UseCase\AutoSubscriber;
use Clearbooks\Dilex\Endpoint;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserToggleAutoSubscribe implements Endpoint
{
    /**
     * @var IdentityProvider
     */
    private $userInformationProvider;

    /**
     * @var AutoSubscriber
     */
    private $autoSubscriber;

    /**
     * UserToggleAutoSubscribe constructor.
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
        if(!isset($userId)) {
            return new JsonResponse("No user ID provided.", 400);
        }
        if($this->autoSubscriber->isUserAutoSubscribed()) {
            $this->autoSubscriber->unSubscribe();
        } else {
            $this->autoSubscriber->subscribe();
        }
        return new JsonResponse(['result' =>true]);
    }
}