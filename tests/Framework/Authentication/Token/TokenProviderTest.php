<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 02/09/15
 * Time: 14:07
 */

namespace Clearbooks\LabsApi\Framework\Authentication\Token;


use Clearbooks\LabsApi\Authentication\Tokens\TokenProvider;
use DateTime;
use Emarref\Jwt\Algorithm\Hs512;
use Emarref\Jwt\Claim\PublicClaim;
use Emarref\Jwt\Encryption\Asymmetric;
use Emarref\Jwt\Encryption\Factory as EncryptionFactory;
use Emarref\Jwt\Encryption\Symmetric;
use Emarref\Jwt\Jwt;
use Emarref\Jwt\Token;

class TokenProviderTest extends \PHPUnit_Framework_TestCase
{

    const USER_ID = '1';
    const GROUP_ID = '1';

    /**
     * @var Token
     */
    private $token;

    /**
     * @var Jwt
     */
    private $jwt;

    /**
     * @var Hs512
     */
    private $algorithm;

    /**
     * @var Asymmetric|Symmetric
     */
    private $encryption;

    /**
     * @return TokenProvider
     */
    private function createTokenProvider()
    {
        $serializedToken = $this->jwt->serialize($this->token, $this->encryption);

        $tokenProvider = new TokenProvider($this->jwt, $this->algorithm, new MockTokenRequest($serializedToken));
        return $tokenProvider;
    }

    /**
     * @return string
     */
    private function getNonExpiredDate()
    {
        $expDate = new DateTime();
        $expDate->modify('+1 day');
        return $expDate->format('U');
    }

    /**
     * @return string
     */
    private function getExpiredDate()
    {
        $date = new DateTime();
        $date->modify('-1 day');
        return $date->format('U');
    }

    /**
     * @internal param Token $token
     */
    private function addValidExpiryDate()
    {
        $this->token->addClaim(new PublicClaim('exp', $this->getNonExpiredDate()));
    }

    /**
     * @internal param Token $token
     */
    private function addValidUserId()
    {
        $this->token->addClaim(new PublicClaim('userId', self::USER_ID));
    }

    /**
     * @internal param Token $token
     */
    private function addValidGroupId()
    {
        $this->token->addClaim(new PublicClaim('groupId', self::GROUP_ID));
    }

    /**
     * @internal param Token $token
     */
    private function addValidAppId()
    {
        $this->token->addClaim(new PublicClaim('appId', 'labs'));
    }

    private function addAllButAppId()
    {
        $this->addValidExpiryDate();
        $this->addValidUserId();
        $this->addValidGroupId();
    }

    private function createValidToken()
    {
        $this->addValidExpiryDate();
        $this->addValidUserId();
        $this->addValidGroupId();
        $this->addValidAppId();
    }

    public function setUp()
    {
        $this->jwt = new Jwt();
        $this->algorithm = new Hs512("shhh... it's a secret");
        $this->encryption = EncryptionFactory::create($this->algorithm);
        $this->token = new Token();
    }

    /**
     * @test
     */
    public function givenValidToken_whenVerifyingToken_returnTrue()
    {
        $this->createValidToken();

        $tokenProvider = $this->createTokenProvider();
        $this->assertEquals(true, $tokenProvider->verifyToken());
    }

    /**
     * @test
     */
    public function givenTokenWithoutGroupId_whenVerifyingToken_returnTrue()
    {
        $this->addValidExpiryDate();
        $this->addValidUserId();
        $this->addValidAppId();

        $tokenProvider = $this->createTokenProvider();
        $this->assertEquals(true, $tokenProvider->verifyToken());
    }

    /**
     * @test
     */
    public function givenExpiredToken_whenVerifyingToken_returnFalse()
    {
        $this->token->addClaim(new PublicClaim('exp', $this->getExpiredDate()));
        $this->addValidUserId();
        $this->addValidGroupId();
        $this->addValidAppId();

        $tokenProvider = $this->createTokenProvider();
        $this->assertEquals(false, $tokenProvider->verifyToken());
    }

    /**
     * @test
     */
    public function givenTokenWithoutUserId_whenVerifyingToken_returnFalse()
    {
        $this->addValidExpiryDate();
        $this->addValidGroupId();
        $this->addValidAppId();

        $tokenProvider = $this->createTokenProvider();
        $this->assertEquals(false, $tokenProvider->verifyToken());
    }

    /**
     * @test
     */
    public function givenTokenWithInvalidAppId_whenVerifyingToken_returnFalse()
    {
        $this->token->addClaim(new PublicClaim('appId', 'irishWolfhounds'));

        $this->addAllButAppId();

        $tokenProvider = $this->createTokenProvider();
        $this->assertEquals(false, $tokenProvider->verifyToken());
    }

    /**
     * @test
     */
    public function givenTokenWithNoAppId_whenVerifyingToken_returnFalse()
    {
        $this->addAllButAppId();

        $tokenProvider = $this->createTokenProvider();
        $this->assertEquals(false, $tokenProvider->verifyToken());
    }

    /**
     * @test
     */
    public function givenValidToken_whenSettingToken_getCorrectUserAndGroupId()
    {
        $this->createValidToken();

        $tokenProvider = $this->createTokenProvider();
        $this->assertEquals(self::USER_ID, $tokenProvider->getUserId());
        $this->assertEquals(self::GROUP_ID, $tokenProvider->getGroupId());
    }

    /**
     * @test
     */
    public function givenTokenWithInvalidSignature_whenValidatingToken_returnFalse()
    {
        $this->createValidToken();

        $this->token->setSignature("broken");
        $token = $this->jwt->serialize($this->token,EncryptionFactory::create(new Hs512("tell everyone the secret")));
        $request = new MockTokenRequest($token);
        $tokenProvider = new TokenProvider($this->jwt, $this->algorithm, $request);
        $this->assertEquals(false, $tokenProvider->verifyToken());
    }
}
