<?php
/**
 * Created by PhpStorm.
 * User: Volodymyr
 * Date: 18/09/2015
 * Time: 12:32
 */

namespace Clearbooks\LabsApi\Feedback;


use Clearbooks\Dilex\Endpoint;
use Clearbooks\Dilex\JwtGuard\IdentityProvider;
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
     * @var IdentityProvider
     */
    private $identityProvider;

    /**
     * AddFeedbackForToggle constructor.
     * @param LabsAddFeedbackForToggle $addFeedbackForToggle
     * @param IdentityProvider $identityProvider
     */
    public function __construct( LabsAddFeedbackForToggle $addFeedbackForToggle, IdentityProvider $identityProvider )
    {
        $this->addFeedbackForToggle = $addFeedbackForToggle;
        $this->identityProvider = $identityProvider;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function execute( Request $request )
    {
        $toggleId = $request->request->get( self::TOGGLE_ID );
        $mood = (bool) $request->request->get( self::MOOD );
        $message = $request->request->get( self::MESSAGE );
        $userId = $this->identityProvider->getUserId();
        $groupId = $this->identityProvider->getGroupId();

        if ( !isset( $toggleId ) || !is_bool( $mood ) || !isset( $message ) || !isset( $userId ) || !isset( $groupId ) ) {
            return new JsonResponse( "Missing arguments error.", 400 );
        }

        $feedbackSucessful = $this->addFeedbackForToggle->execute( $toggleId, $mood, $message, $userId, $groupId );

        if ( $feedbackSucessful ) {
            return new JsonResponse( [ 'result' => true ] );
        }

        return new JsonResponse( "Missing arguments error.", 400 );

    }
}