<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 01/09/15
 * Time: 09:35
 */

namespace Clearbooks\LabsApi\Authentication\Tokens;

use Emarref\Jwt\Token;

interface TokenAuthenticationProvider
{
    public function verifyToken();
}