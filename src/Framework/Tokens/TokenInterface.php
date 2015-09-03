<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 03/09/15
 * Time: 12:29
 */

namespace Clearbooks\LabsApi\Framework\Tokens;


interface TokenInterface
{
    public function setToken($serializedToken);
}