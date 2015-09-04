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
use Clearbooks\LabsApi\Authentication\Tokens\UserInformationProvider;
use Clearbooks\LabsApi\EndpointTest;
use Clearbooks\LabsApi\User\MockTokenProvider;
use Clearbooks\LabsMysql\Toggle\Entity\Toggle;

class GetTogglesActivatedByUserTest extends EndpointTest
{
    /**
     * @param ActivatableToggle[] $expectedToggles
     * @param UserInformationProvider $tokenProvider
     */
    public function createCollectionMocks($expectedToggles, UserInformationProvider $tokenProvider)
    {
        $this->endpoint = new GetTogglesActivatedByUser(
            new GetActivatedToggles(
                new ActivatedToggleGatewayStub(
                    $expectedToggles
                )
            ),
            $tokenProvider
        );
    }

    /**
     * @test
     */
    public function givenNoUserIdentifier_WhenGettingActiveToggles_return400()
    {
        $this->createCollectionMocks([], new MockTokenProvider(null));
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
        $this->createCollectionMocks($expectedToggles, new MockTokenProvider(1));
        $this->executeWithQuery(['userId' => 0]);

        $this->assertJsonResponse([
            [
                'key' => 'cats',
            ]
        ]);
    }


}
