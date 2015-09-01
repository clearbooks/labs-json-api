<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 01/09/15
 * Time: 10:36
 */

namespace Clearbooks\LabsApi\Framework\Tokens;


use Emarref\Jwt\Algorithm\AlgorithmInterface;
use Emarref\Jwt\Encryption\Factory;
use Emarref\Jwt\Jwt;
use Emarref\Jwt\Token;
use Emarref\Jwt\Verification\Context;
use Symfony\Component\HttpFoundation\Request;

class TokenProvider implements AuthenticationProvider, UserInformationProvider
{
    /**
     * @var Jwt
     */
    private $jwt;
    /**
     * @var AlgorithmInterface
     */
    private $algorithm;
    /**
     * @var \Emarref\Jwt\Encryption\Asymmetric|\Emarref\Jwt\Encryption\Symmetric
     */
    private $encryption;
    /**
     * @var Context
     */
    private $context;
    /**
     * @var Token
     */
    private $token;

    /**
     * @param Request $request
     * @param Jwt $jwt
     * @param AlgorithmInterface $algorithm
     */
    public function __construct(Request $request, Jwt $jwt, AlgorithmInterface $algorithm)
    {
        $this->jwt = $jwt;
        $this->algorithm = $algorithm;
        $this->encryption = Factory::create($algorithm);
        $this->context = new Context($this->encryption);
        $this->token = $this->jwt->deserialize($request->headers->get('Authorization'));
    }

    public function verifyToken()
    {
        return $this->jwt->verify($this->token, $this->context);
    }

    public function getUserId()
    {
        // TODO: Implement getUserId() method.
    }

    public function getGroupId()
    {
        // TODO: Implement getGroupId() method.
    }
}