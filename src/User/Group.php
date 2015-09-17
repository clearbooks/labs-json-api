<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 07/09/15
 * Time: 09:18
 */

namespace Clearbooks\LabsApi\User;


use Clearbooks\Dilex\JwtGuard\IdentityProvider;

class Group implements \Clearbooks\Labs\Client\Toggle\Entity\Group
{
    /**
     * Group constructor.
     * @param IdentityProvider $userInformationProvider
     */
    public function __construct(IdentityProvider $userInformationProvider)
    {
        $this->id = $userInformationProvider->getGroupId();
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
}