<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 07/09/15
 * Time: 09:14
 */

namespace Clearbooks\LabsApi\User;


use Clearbooks\LabsApi\Authentication\Tokens\UserInformationProvider;

class User implements \Clearbooks\Labs\Client\Toggle\Entity\User
{
    private $id;

    /**
     * User constructor.
     * @param UserInformationProvider $userInformationProvider
     */
    public function __construct(UserInformationProvider $userInformationProvider)
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