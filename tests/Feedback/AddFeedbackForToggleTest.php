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
    public function givenNoParrameters_return400WithAllParametersMissingResponse()
    {
        $this->executeWithPostParams( [ ] );
        $this->assert400();
    }

    /**
     * @test
     */
    public function givenNoToggleId_return400WithMissingToggleIdResponse()
    {
        $this->executeWithPostParams( [ AddFeedbackForToggle::MOOD => true, AddFeedbackForToggle::MESSAGE => "hello world" ] );
        $this->assert400();
    }
}
