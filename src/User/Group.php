<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 07/09/15
 * Time: 09:18
 */

namespace Clearbooks\LabsApi\User;


use Clearbooks\LabsApi\Authentication\Tokens\UserInformationProvider;

class Group implements \Clearbooks\Labs\Client\Toggle\Entity\Group
{
    /**
     * Group constructor.
     * @param UserInformationProvider $userInformationProvider
     */
    public function __construct(UserInformationProvider $userInformationProvider)
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