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

    /**
     * @var bool
     */
    private $isAdmin;

    public function __construct($userId, $groupId = null, $isAdmin = true) {
        $this->userId = $userId;
        $this->groupId = $groupId;
        $this->isAdmin = $isAdmin;
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

    public function getIsAdmin() {
        return $this->isAdmin;
    }
}