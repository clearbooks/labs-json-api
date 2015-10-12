<?php
/**
 * Created by PhpStorm.
 * User: dan
 * Date: 27/08/15
 * Time: 09:55
 */

namespace Clearbooks\LabsApi\Toggle;
use Clearbooks\Dilex\JwtGuard\IdentityProvider;
use Clearbooks\Labs\Toggle\Entity\MarketableToggle;
use Clearbooks\Labs\Toggle\Gateway\GetAllTogglesGatewayStub;
use Clearbooks\Labs\Toggle\GetActivatedToggles;
use Clearbooks\Labs\Toggle\PassingToggleCheckerStub;
use Clearbooks\LabsApi\EndpointTest;
use Clearbooks\LabsApi\User\MockIdentityProvider;
use Clearbooks\LabsMysql\Toggle\Entity\Toggle;

class GetTogglesActivatedByUserTest extends EndpointTest
{
    /**
     * @param MarketableToggle[] $expectedToggles
     * @param IdentityProvider $tokenProvider
     */
    public function createCollectionMocks($expectedToggles, IdentityProvider $tokenProvider)
    {
        $this->endpoint = new GetTogglesActivatedByUser(
            new GetActivatedToggles(
                new GetAllTogglesGatewayStub(
                    $expectedToggles
                ),
                new PassingToggleCheckerStub
            ),
            $tokenProvider
        );
    }

    /**
     * @test
     */
    public function givenNoUserIdentifier_WhenGettingActiveToggles_return400()
    {
        $this->createCollectionMocks([], new MockIdentityProvider(null));
        $this->executeWithQuery([]);
        $this->assertEquals(400, $this->response->getStatusCode());
    }

    /**
     * @test
     */
    public function givenUserIdentifier_WhenGettingTwoActiveToggles_returnActiveToggles()
    {
        $expectedToggles = [
            new Toggle('1', 'cats', '0', true, "", "", "", "", "", "", "", "", "cats"),
            new Toggle('2', 'dogs', '0', true, "", "", "", "", "", "", "", "", "dogs")
        ];
        $this->createCollectionMocks($expectedToggles, new MockIdentityProvider(1));
        $this->executeWithQuery(['userId' => 0]);

        $this->assertJsonResponse([
            'cats' => 1,
            'dogs' => 1,
        ]);
    }


}
