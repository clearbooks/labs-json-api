<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 21/08/15
 * Time: 10:17
 */

namespace Clearbooks\LabsApi\Toggle;

use Clearbooks\Labs\Toggle\Gateway\ActivatableToggleGatewayStub;
use Clearbooks\Labs\Toggle\IsToggleActive;
use Clearbooks\LabsApi\EndpointTest;

class GetIsToggleActiveTest extends EndpointTest
{
    /**
     * @var boolean[]
     */
    private $collectionMock;

    public function setUp(): void
    {
        $this->endpoint = new GetIsToggleActive(
            new IsToggleActive(
                new ActivatableToggleGatewayStub(
                    $this->collectionMock = ['cat' => true, 'dog' => false]
                )
            )
        );
    }

    /**
     * @test
     */
    public function givenNoToggleName_return400()
    {
        $this->executeWithQuery([]);
        $this->assert400();
    }

    /**
     * @test
     */
    public function givenActiveToggleName_assertTrueJsonResponse()
    {
        $this->executeWithQuery(['name' => 'cat']);
        $this->assertJsonResponse([
            'name' => 'cat',
            'isActive' => true
        ]);
    }

    /**
     * @test
     */
    public function givenNonActiveToggleName_assertFalseJsonResponse()
    {
        $this->executeWithQuery(['name' => 'dog']);
        $this->assertJsonResponse([
            'name' => 'dog',
            'isActive' => false
        ]);
    }
}
