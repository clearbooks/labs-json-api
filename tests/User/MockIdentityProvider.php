<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 02/09/15
 * Time: 10:26
 */

namespace Clearbooks\LabsApi\User;
use Clearbooks\Dilex\JwtGuard\IdentityProvider;

class MockIdentityProvider implements IdentityProvider
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
}