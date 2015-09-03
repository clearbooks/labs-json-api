<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 01/09/15
 * Time: 10:36
 */

namespace Clearbooks\LabsApi\Authentication\Tokens;


use DateTime;
use Emarref\Jwt\Algorithm\AlgorithmInterface;
use Emarref\Jwt\Encryption\Factory as EncryptionFactory;
use Emarref\Jwt\Exception\VerificationException;
use Emarref\Jwt\Jwt;
use Emarref\Jwt\Token;
use Emarref\Jwt\Verification\Context;

class TokenProvider implements TokenAuthenticationProvider, UserInformationProvider
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
        $this->encryption = EncryptionFactory::create($algorithm);
        $this->context = new Context($this->encryption);
    }

    public function setToken($serializedToken)
    {
        $this->token = $this->jwt->deserialize($serializedToken);
    }

    public function verifyToken()
    {
        if(!($this->isNotExpired() && $this->hasUserId() && $this->isLabsToken())) {
            return false;
        }
        try {
            $this->jwt->verify($this->token, $this->context);
            return true;
        } catch (VerificationException $e) {
            return false;
        }
    }

    public function getUserId()
    {
        return $this->token->getPayload()->findClaimByName('userId')->getValue();
    }

    public function getGroupId()
    {
        return $this->token->getPayload()->findClaimByName('groupId')->getValue();
    }

    private function isNotExpired()
    {
        $today = new DateTime();
        $exp = new DateTime();
        $exp->setTimestamp($this->token->getPayload()->findClaimByName('exp')->getValue());
        return $exp > $today;
    }
    private function hasUserId()
    {
        $userId = $this->token->getPayload()->findClaimByName('userId');
        return isset($userId);
    }

    private function isLabsToken()
    {
        $appId = $this->token->getPayload()->findClaimByName('appId');
        return(isset($appId) && $appId->getValue() == "labs");
    }
}