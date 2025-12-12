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
    /**
     * @var string
     */
    private $userId;

    /**
     * @var string
     */
    private $groupId;

    /**
     * @var bool
     */
    private $isAdmin;

    /**
     * @var array
     */
    private $segments;

    /**
     * @param string $userId
     * @param string $groupId
     * @param array $segments
     * @param bool $isAdmin
     */
    public function __construct($userId, $groupId = null, array $segments = [ ], $isAdmin = true) {
        $this->userId = $userId;
        $this->groupId = $groupId;
        $this->segments = $segments;
        $this->isAdmin = $isAdmin;
    }

    /**
     * @return bool
     */
    public function verifyToken()
    {
        return true;
    }

    /**
     * @return string
     */
    public function getUserId(): mixed
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getGroupId(): mixed
    {
        return $this->groupId;
    }

    /**
     * @return array
     */
    public function getSegments(): array
    {
        return $this->segments;
    }

    /**
     * @return bool
     */
    public function getIsAdmin(): bool
    {
        return $this->isAdmin;
    }

    /**
     * @param string $userId
     */
    public function setUserId( $userId )
    {
        $this->userId = $userId;
    }

    /**
     * @param string $groupId
     */
    public function setGroupId( $groupId )
    {
        $this->groupId = $groupId;
    }

    /**
     * @param boolean $isAdmin
     */
    public function setIsAdmin( $isAdmin )
    {
        $this->isAdmin = $isAdmin;
    }

    /**
     * @param array $segments
     */
    public function setSegments( $segments )
    {
        $this->segments = $segments;
    }
}
