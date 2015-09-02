<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 01/09/15
 * Time: 14:17
 */

namespace Clearbooks\LabsApi\Framework;


use Clearbooks\LabsApi\Framework\Tokens\TokenProviderInterface;
use Emarref\Jwt\Algorithm\AlgorithmInterface;
use Emarref\Jwt\Algorithm\Hs512;
use Emarref\Jwt\Token;
use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AuthenticationProvider
{
    private $algorithm;
    private $tokenProvider;

    public function __construct(AlgorithmInterface $algorithm, TokenProviderInterface $tokenProvider)
    {
        $this->algorithm = $algorithm;
        $this->tokenProvider = $tokenProvider;
    }

    public function verify(Request $request)
    {
        $this->tokenProvider->setToken($request->headers->get('Authorization'));
        if(!$this->algorithm instanceof Hs512) {
            return new JsonResponse("Algorithm was not Hs512", 403);
        }
        if(!$this->tokenProvider->verifyToken()) {
            return new JsonResponse("Couldn't verify token", 403);
        }
        return null;
    }
}