<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 02/09/15
 * Time: 10:26
 */

namespace Clearbooks\LabsApi\User;


use Clearbooks\LabsApi\Framework\Tokens\TokenProviderInterface;
use Symfony\Component\HttpFoundation\Request;

class MockTokenProvider implements TokenProviderInterface
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

    public function setToken(Request $request)
    {
        //Empty
    }
}