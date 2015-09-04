<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 02/09/15
 * Time: 10:26
 */

namespace Clearbooks\LabsApi\User;


use Clearbooks\LabsApi\Authentication\Tokens\TokenAuthenticationProvider;
use Clearbooks\LabsApi\Authentication\Tokens\UserInformationProvider;

class MockTokenProvider implements UserInformationProvider, TokenAuthenticationProvider
{
    private $userId;

    private $groupId;

    public function __construct($userId, $groupId = null) {
        $this->userId = $userId;
        $this->groupId = $groupId;
    }

    public function verifyToken()
    {
        return true;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getGroupId() {
        return $this->groupId;
    }

    public function setToken($serializedToken)
    {
        //Empty
    }
}