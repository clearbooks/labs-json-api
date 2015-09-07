<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 04/09/15
 * Time: 15:41
 */

namespace Clearbooks\LabsApi\Toggle;


use Clearbooks\Labs\Client\Toggle\Entity\User;

class UserStub implements User
{
    private $id;

    /**
     * UserStub constructor.
     */
    public function __construct()
    {
        $this->id = 1;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
}