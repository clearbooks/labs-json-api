<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 03/09/15
 * Time: 11:57
 */

namespace Clearbooks\LabsApi\User;


use Clearbooks\LabsApi\Authentication\Tokens\TokenAuthenticationProvider;

class FailingMockTokenProvider implements TokenAuthenticationProvider
{
    public function setToken($serializedToken)
    {
        // empty
    }
    public function verifyToken()
    {
        return false;
    }
}