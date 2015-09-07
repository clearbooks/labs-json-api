<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 01/09/15
 * Time: 10:26
 */

namespace Clearbooks\LabsApi\Authentication\Tokens;


interface UserInformationProvider
{
    public function getUserId();

    public function getGroupId();
}