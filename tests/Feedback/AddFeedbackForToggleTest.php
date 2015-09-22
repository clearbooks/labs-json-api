<?php
/**
 * Created by PhpStorm.
 * User: Volodymyr
 * Date: 18/09/2015
 * Time: 11:59
 */

namespace Clearbooks\LabsApi\Feedback;

use Clearbooks\Labs\Feedback\AddFeedbackForToggle as LabsAddFeedbackForToggle;
use Clearbooks\Labs\Feedback\Gateway\InsertFeedbackForToggleGatewaySpy;
use Clearbooks\LabsApi\EndpointTest;
use Symfony\Component\HttpFoundation\JsonResponse;

class AddFeedbackForToggleTest extends EndpointTest
{
    public function setUp()
    {
        $this->endpoint = new AddFeedbackForToggle( new LabsAddFeedbackForToggle( new InsertFeedbackForToggleGatewaySpy() ) );
    }

    /**
     * @test
     */
    public function givenNoParrameters_return400()
    {
        $this->executeWithPostParams( [ ] );
        $this->assert400();
    }

    /**
     * @test
     */
    public function givenNoToggleId_return400()
    {
        $this->executeWithPostParams( [ AddFeedbackForToggle::MOOD => true, AddFeedbackForToggle::MESSAGE => "hello world" ] );
        $this->assert400();
    }

    /**
     * @test
     */
    public function givenNoMood_return400()
    {
        $this->executeWithPostParams( [ AddFeedbackForToggle::TOGGLE_ID => "1234123412", AddFeedbackForToggle::MESSAGE => "hello world" ] );
        $this->assert400();
    }

    /**
     * @test
     */
    public function givenNoMessage_return400()
    {
        $this->executeWithPostParams( [ AddFeedbackForToggle::TOGGLE_ID => "123412341234fasdf", AddFeedbackForToggle::MOOD => false ] );
        $this->assert400();
    }

    /**
     * @test
     */
    public function givenAllParameters_returnJsonResponseResultTrue()
    {
        $this->executeWithPostParams( [ AddFeedbackForToggle::TOGGLE_ID => "1", AddFeedbackForToggle::MOOD => false, AddFeedbackForToggle::MESSAGE => "hello" ] );
        $this->assertJsonResponse( [ 'result' => true ] );
    }
}
