<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 07/09/15
 * Time: 16:38
 */

namespace Clearbooks\LabsApi\User;


use Clearbooks\Labs\AutoSubscribe\Gateway\AutoSubscriptionProviderSpy;
use Clearbooks\Labs\AutoSubscribe\UserAutoSubscriber;
use Clearbooks\LabsApi\EndpointTest;

class IsUserAutoSubscribedTest extends EndpointTest
{
    public function setupEndpoint($userId, $isSubscribed)
    {
        $userInformationProvider = new MockIdentityProvider($userId);

        $this->endpoint = new IsUserAutoSubscribed(
            new UserAutoSubscriber(
                new User(
                    $userInformationProvider
                ),
                new AutoSubscriptionProviderSpy($isSubscribed)
            ),
            $userInformationProvider
        );
    }

    /**
     * @test
     */
    public function givenNoUserId_whenChecking_return400()
    {
        $this->setupEndpoint(null, true);

        $this->executeWithQuery([]);

        $this->assert400();
    }

    /**
     * @test
     */
    public function givenNotAutoSubscribed_whenChecking_returnFalse()
    {
        $this->setupEndpoint('1', false);

        $this->executeWithQuery([]);

        $this->assertJsonResponse(['autoSubscribed' => false]);
    }

    /**
     * @test
     */
    public function givenSubscribedUser_whenChecking_returnTrue()
    {
        $this->setupEndpoint('1', true);

        $this->executeWithQuery([]);

        $this->assertJsonResponse(['autoSubscribed' => true]);
    }
}
