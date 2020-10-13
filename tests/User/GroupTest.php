<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 07/09/15
 * Time: 11:22
 */

namespace Clearbooks\LabsApi\User;

use PHPUnit\Framework\TestCase;

class GroupTest extends TestCase
{
    /** @var Group */
    private $group;

    public function setUp(): void
    {
        $this->group = new Group(new MockIdentityProvider('1', '1'));
    }

    /**
     * @test
     */
    public function givenGroupId_whenGettingId_returnCorrectId()
    {
        $this->assertEquals('1', $this->group->getId());
    }

}
