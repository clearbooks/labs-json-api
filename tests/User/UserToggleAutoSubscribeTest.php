<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 07/09/15
 * Time: 12:44
 */

namespace Clearbooks\LabsApi\User;


use Clearbooks\Labs\AutoSubscribe\Gateway\AutoSubscriptionProviderSpy;
use Clearbooks\Labs\AutoSubscribe\UserAutoSubscriber;
use Clearbooks\LabsApi\EndpointTest;

class UserToggleAutoSubscribeTest extends EndpointTest
{
    private $user;
    /** @var AutoSubscriptionProviderSpy */
    private $autoSubscriptionProvider;

    public function setupEndpoint($userId, $subscribed)
    {
        $this->autoSubscriptionProvider = new AutoSubscriptionProviderSpy($subscribed);

        $userInformationProvider = new MockTokenProvider($userId);
        $this->user = new User($userInformationProvider);

        $this->endpoint = new UserToggleAutoSubscribe(
            new UserAutoSubscriber(
                $this->user,
                $this->autoSubscriptionProvider
            ),
            $userInformationProvider
        );
    }

    /**
     * @test
     */
    public function givenNoUserIdInToken_whenSubscribing_return400()
    {
        $this->setupEndpoint(null, false);

        $this->executeWithQuery([]);

        $this->assert400();
    }

    /**
     * @test
     */
    public function givenCorrectUserIdAndNotSubscribed_whenSubscribing_returnTrueAndSubscribe()
    {
        $this->setupEndpoint('1', false);

        $this->executeWithQuery([]);

        $this->assertJsonResponse([true]);
        $this->assertEquals(true, $this->autoSubscriptionProvider->isUpdateSubscriptionParamSubscribe());
    }

    /**
     * @test
     */
    public function givenCorrectUserIdAndSubscribed_whenSubscribing_returnTrueAndStopSubscribing()
    {
        $this->setupEndpoint('1', true);

        $this->executeWithQuery([]);

        $this->assertJsonResponse([true]);
        $this->assertEquals(false, $this->autoSubscriptionProvider->isUpdateSubscriptionParamSubscribe());
    }
}
