<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 07/09/15
 * Time: 09:14
 */

namespace Clearbooks\LabsApi\User;
use Clearbooks\Dilex\JwtGuard\IdentityProvider;

class User implements \Clearbooks\Labs\Client\Toggle\Entity\User, \Clearbooks\Labs\AutoSubscribe\Entity\User
{
    private $id;

    /**
     * User constructor.
     * @param IdentityProvider $userInformationProvider
     */
    public function __construct(IdentityProvider $userInformationProvider)
    {
        $this->id = $userInformationProvider->getUserId();
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
}