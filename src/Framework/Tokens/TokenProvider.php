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

class TokenProvider implements TokenProviderInterface
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
     * @param Jwt $jwt
     * @param AlgorithmInterface $algorithm
     */
    public function __construct(Jwt $jwt, AlgorithmInterface $algorithm)
    {
        $this->jwt = $jwt;
        $this->algorithm = $algorithm;
        $this->encryption = Factory::create($algorithm);
        $this->context = new Context($this->encryption);
    }

    public function setToken(Request $request)
    {
        $this->token = $this->jwt->deserialize($request->headers->get('Authorization'));

    }

    public function verifyToken()
    {
        return $this->jwt->verify($this->token, $this->context);
    }

    public function getUserId()
    {
        return $this->token->getPayload()->findClaimByName('userId')->getValue();
    }

    public function getGroupId()
    {
        return $this->token->getPayload()->findClaimByName('groupId')->getValue();
    }
}