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
use Symfony\Component\HttpFoundation\Request;

class TokenProvider implements TokenAuthenticationProvider, UserInformationProvider
{
    const USER_ID = 'userId';
    const GROUP_ID = 'groupId';
    const APP_ID = 'appId';
    const EXPIRY = 'exp';
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
     * @param Request $request
     */
    public function __construct(Jwt $jwt, AlgorithmInterface $algorithm, Request $request)
    {
        $this->jwt = $jwt;
        $this->algorithm = $algorithm;
        $this->encryption = EncryptionFactory::create($algorithm);
        $this->context = new Context( $this->encryption );

        $header = $request->headers->get( 'Authorization' );
        $this->token = $header ? $this->jwt->deserialize( $header ) : new Token;
    }

    /**
     * Get a claim if we have one or return null
     * @param string $claim the name of the claim
     * @return string|null
     */
    private function getClaimOrNull( $claim )
    {
        $claim = $this->token->getPayload()->findClaimByName( $claim );
        return $claim ? $claim->getValue() : null;
    }

    /**
     * Is this token expired
     * @return bool
     */
    private function isExpired()
    {
        $exp = \DateTime::createFromFormat( 'U', $this->getClaimOrNull( self::EXPIRY ) );
        return !$exp || $exp <= ( new DateTime );
    }

    /**
     * Does this token have a user id
     * @return bool
     */
    private function hasUserId()
    {
        return (bool)$this->getClaimOrNull( self::USER_ID );
    }

    /**
     * Is this token for labs
     * @return bool
     */
    private function isLabsToken()
    {
        return $this->getClaimOrNull( self::APP_ID ) === 'labs';
    }
    /**
     * Verify the token
     * @return bool
     */
    public function verifyToken()
    {
        if( $this->isExpired() || !$this->hasUserId() || !$this->isLabsToken() ) {
            return false;
        }

        try {
            $this->jwt->verify($this->token, $this->context);
            return true;
        } catch (VerificationException $e) {
            return false;
        }
    }

    /**
     * Get the user id from the token
     * @return mixed|null
     */
    public function getUserId()
    {
        return $this->getClaimOrNull( self::USER_ID );
    }

    /**
     * Get the group id from the token
     * @return mixed|null
     */
    public function getGroupId()
    {
        return $this->getClaimOrNull( self::GROUP_ID );
    }
}