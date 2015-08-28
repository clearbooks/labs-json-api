<?php
namespace Clearbooks\LabsApi\Auth;

use Clearbooks\LabsApi\Framework\Endpoint;
use Emarref\Jwt\Algorithm\None;
use Emarref\Jwt\Encryption\Factory;
use Emarref\Jwt\Exception\VerificationException;
use Emarref\Jwt\Jwt;
use Emarref\Jwt\Token;
use Emarref\Jwt\Verification\Context;
use Symfony\Component\HttpFoundation\Request;

/**
 * Created by PhpStorm.
 * User: dan
 * Date: 28/08/15
 * Time: 11:00
 */
class AuthTest implements Endpoint
{

    /**
     * @param Request $request
     * @return string
     */
    public function execute(Request $request)
    {

        $jwt = new Jwt();
        $algorithm = new None();
        $encryption = Factory::create($algorithm);
        $context = new Context($encryption);
        $token = new Token();
        $jwt->serialize($token, $encryption);

        try {
            return $jwt->verify($token, $context);
        } catch (VerificationException $e) {
            return $e;
        }

    }
}