<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 07/09/15
 * Time: 12:38
 */

namespace Clearbooks\LabsApi\User;

use Clearbooks\Labs\AutoSubscribe\UseCase\AutoSubscriber;
use Clearbooks\LabsApi\Authentication\Tokens\UserInformationProvider;
use Clearbooks\LabsApi\Framework\Endpoint;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserToggleAutoSubscribe implements Endpoint
{
    /**
     * @var UserInformationProvider
     */
    private $userInformationProvider;

    /**
     * @var AutoSubscriber
     */
    private $autoSubscriber;

    /**
     * UserToggleAutoSubscribe constructor.
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