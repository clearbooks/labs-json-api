<?php
/**
 * Created by PhpStorm.
 * User: Volodymyr
 * Date: 18/09/2015
 * Time: 12:32
 */

namespace Clearbooks\LabsApi\Feedback;


use Clearbooks\Dilex\Endpoint;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use \Clearbooks\Labs\Feedback\AddFeedbackForToggle as LabsAddFeedbackForToggle;

class AddFeedbackForToggle implements Endpoint
{
    const TOGGLE_ID = 'toggleId';
    const MOOD = 'mood';
    const MESSAGE = 'message';
    /**
     * @var AddFeedbackForToggle
     */
    private $addFeedbackForToggle;

    /**
     * AddFeedbackForToggle constructor.
     * @param LabsAddFeedbackForToggle $addFeedbackForToggle
     */
    public function __construct( LabsAddFeedbackForToggle $addFeedbackForToggle )
    {
        $this->addFeedbackForToggle = $addFeedbackForToggle;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function execute( Request $request )
    {
        $toggleId = $request->request->get( self::TOGGLE_ID );
        $mood = $request->request->get( self::MOOD );
        $message = $request->request->get( self::MESSAGE );

        if ( !isset( $toggleId ) || !isset( $mood ) || !isset( $message ) ) {
            return new JsonResponse( "Missing arguments error.", 400 );
        }

        $feedbackSucessful = $this->addFeedbackForToggle->execute( $toggleId, $mood, $message );

        if ( $feedbackSucessful ) {
            return new JsonResponse( [ 'result' => true ] );
        }

        return new JsonResponse( "Missing arguments error.", 400 );

    }
}