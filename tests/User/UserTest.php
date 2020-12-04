<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 07/09/15
 * Time: 11:24
 */

namespace Clearbooks\LabsApi\User;

use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /** @var User */
    private $user;

    public function setUp(): void
    {
        $this->user = new User(new MockIdentityProvider('1'));
    }

    /**
     * @test
     */
    public function givenUserId_whenGettingId_returnCorrectId()
    {
        $this->assertEquals('1', $this->user->getId());
    }
}
