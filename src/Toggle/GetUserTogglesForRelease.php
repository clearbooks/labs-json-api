<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 21/08/15
 * Time: 11:28
 */

namespace Clearbooks\LabsApi\Toggle;


use Clearbooks\Labs\Toggle\GetUserTogglesForRelease as LabsGetUserTogglesForRelease;
use Clearbooks\Dilex\Endpoint;
use Clearbooks\LabsMysql\Toggle\Entity\Toggle;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GetUserTogglesForRelease implements Endpoint
{
    /**
     * @var LabsGetUserTogglesForRelease
     */
    private $getUserToggles;

    /**
     * @param LabsGetUserTogglesForRelease $getUserToggles
     */
    public function __construct(LabsGetUserTogglesForRelease $getUserToggles)
    {
        $this->getUserToggles = $getUserToggles;
    }

    public function execute(Request $request)
    {
        $releaseId = $request->get('release');
        if(!isset($releaseId)) {
            return new JsonResponse('Missing release ID', 400);
        }
        /** @var Toggle[] $userToggles */
        $userToggles = $this->getUserToggles->execute($releaseId);
        $json = [];
        foreach ($userToggles as $toggle) {
            $json[] = [
                'name' => $toggle->getName(),
                'marketingInfo' => [
                    'appNotificationCopyText' => $toggle->getAppNotificationCopyText(),
                    'functionalityDescription' => $toggle->getDescriptionOfFunctionality(),
                    'implementationReason' => $toggle->getDescriptionOfImplementationReason(),
                    'locationDescription' => $toggle->getDescriptionOfLocation(),
                    'toggleDescription' => $toggle->getDescriptionOfToggle(),
                    'screenshotUrl' => $toggle->getScreenshotUrl(),
                    'guideUrl' => $toggle->getGuideUrl(),
                ]
            ];
        }

        return new JsonResponse($json);
    }
}