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
    public function setupEndpoint($userId, $subscribed)
    {
        $this->endpoint = new UserToggleAutoSubscribe(
            new UserAutoSubscriber(
                new User(new MockTokenProvider('1')),
                new AutoSubscriptionProviderSpy($subscribed)
            ),
            new MockTokenProvider($userId)
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
    public function givenCorrectUserIdAndNotSubscribed_whenSubscribing_returnTrue()
    {
        $this->setupEndpoint('1', false);

        $this->executeWithQuery([]);

        $this->assertJsonResponse([true]);
    }

    /**
     * @test
     */
    public function givenCorrectUserIdAndSubscribed_whenSubscribing_returnTrue()
    {
        $this->setupEndpoint('1', true);

        $this->executeWithQuery([]);

        $this->assertJsonResponse([true]);
    }
}
