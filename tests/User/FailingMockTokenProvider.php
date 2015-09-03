<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 03/09/15
 * Time: 11:57
 */

namespace Clearbooks\LabsApi\User;


use Clearbooks\LabsApi\Framework\Tokens\TokenProviderInterface;

class FailingMockTokenProvider implements TokenProviderInterface
{

    public function setToken($serializedToken)
    {
        // empty
    }

    public function verifyToken()
    {
        return false;
    }

    public function getUserId()
    {
        // empty
    }

    public function getGroupId()
    {
        // empty
    }
}