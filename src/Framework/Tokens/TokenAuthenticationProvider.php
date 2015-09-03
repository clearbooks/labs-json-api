<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 01/09/15
 * Time: 09:35
 */

namespace Clearbooks\LabsApi\Framework\Tokens;

use Emarref\Jwt\Token;

interface TokenAuthenticationProvider extends TokenInterface
{
    public function verifyToken();
}