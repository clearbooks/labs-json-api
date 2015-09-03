<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 03/09/15
 * Time: 11:44
 */

namespace Authentication;


use Clearbooks\LabsApi\Authentication\JwtGuard;
use Clearbooks\LabsApi\User\FailingMockTokenProvider;
use Clearbooks\LabsApi\User\MockTokenProvider;
use Emarref\Jwt\Algorithm\Hs512;
use Emarref\Jwt\Algorithm\None;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class JwtGuardTest extends \PHPUnit_Framework_TestCase
{
    private $request;

    public function setUp()
    {
        $this->request = new Request();
        $this->request->headers->set('Authorization', 'Token');
    }

    /**
     * @test
     */
    public function givenValidRequest_whenExecuting_returnNull()
    {
        $guard = new JwtGuard(new MockTokenProvider('1'), new Hs512('shh.. secrets'));

        $this->assertNull($guard->execute($this->request));
    }

    /**
     * @test
     */
    public function givenNotHs521Encryption_whenExecuting_return403()
    {
        $guard = new JwtGuard(new MockTokenProvider('1'), new None());

        $this->assert403($guard->execute($this->request));
    }

    /**
     * @test
     */
    public function givenInvalidToken_whenExecuting_return403()
    {
        $guard = new JwtGuard(new FailingMockTokenProvider(), new Hs512("shh.. secrets"));

        $this->assert403($guard->execute($this->request));
    }

    private function assert403(JsonResponse $jsonResponse)
    {
        $this->assertEquals(403, $jsonResponse->getStatusCode());
    }
}
