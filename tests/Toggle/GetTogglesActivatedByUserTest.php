<?php
/**
 * Created by PhpStorm.
 * User: dan
 * Date: 27/08/15
 * Time: 09:55
 */

namespace Clearbooks\LabsApi\Toggle;


use Clearbooks\Labs\Toggle\Entity\ActivatableToggle;
use Clearbooks\Labs\Toggle\Gateway\ActivatedToggleGatewayStub;
use Clearbooks\Labs\Toggle\GetActivatedToggles;
use Clearbooks\LabsApi\EndpointTest;
use Clearbooks\LabsMysql\Toggle\Entity\Toggle;

class GetTogglesActivatedByUserTest extends EndpointTest
{
    /**
     * @param ActivatableToggle[] $expectedToggles
     */
    public function createCollectionMocks($expectedToggles)
    {
        $this->endpoint = new GetTogglesActivatedByUser(
            new GetActivatedToggles(
                new ActivatedToggleGatewayStub(
                    $expectedToggles
                )
            )
        );
    }

    /**
     * @test
     */
    public function givenNoUserIdentifier_WhenGettingActiveToggles_return400()
    {
        $this->createCollectionMocks([]);
        $this->executeWithQuery([]);
        $this->assertEquals(400, $this->response->getStatusCode());
    }

    /**
     * @test
     */
    public function givenUserIdentifier_WhenGettingActiveToggles_returnActiveToggles()
    {
        $expectedToggles = [
            new Toggle('cats', '1', true)
        ];
        $this->createCollectionMocks($expectedToggles);
        $this->executeWithQuery(['userId' => 0]);

        $this->assertJsonResponse([
            [
                'name' => 'cats',
                'release' => '1'
            ]
        ]);
    }


}
