<?php
namespace Clearbooks\LabsApi\Authentication;

use Clearbooks\LabsApi\Framework\Middleware;
use Clearbooks\LabsApi\Authentication\Tokens\TokenAuthenticationProvider;
use Clearbooks\LabsApi\Authentication\Tokens\TokenProvider;
use Emarref\Jwt\Algorithm\AlgorithmInterface;
use Emarref\Jwt\Algorithm\Hs512;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class JwtGuard implements Middleware
{
    /**
     * @var TokenProvider
     */
    private $tokenProvider;
    /**
     * @var AlgorithmInterface
     */
    private $algorithm;

    /**
     * JwtGuard constructor.
     * @param TokenAuthenticationProvider $tokenProvider
     * @param AlgorithmInterface $algorithm
     */
    public function __construct(TokenAuthenticationProvider $tokenProvider, AlgorithmInterface $algorithm)
    {
        $this->tokenProvider = $tokenProvider;
        $this->algorithm = $algorithm;
    }

    public function execute( Request $request )
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