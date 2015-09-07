<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 07/09/15
 * Time: 11:24
 */

namespace Clearbooks\LabsApi\User;


class UserTest extends \PHPUnit_Framework_TestCase
{
    /** @var User */
    private $user;

    public function setUp()
    {
        $this->user = new User(new MockTokenProvider('1'));
    }

    /**
     * @test
     */
    public function givenUserId_whenGettingId_returnCorrectId()
    {
        $this->assertEquals('1', $this->user->getId());
    }
}
