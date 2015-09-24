<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 21/08/15
 * Time: 14:58
 */

namespace Clearbooks\LabsApi\Toggle;


use Clearbooks\Dilex\Endpoint;
use Clearbooks\Labs\Toggle\GetGroupTogglesForRelease as labsGetGroupToggles;
use Clearbooks\LabsMysql\Toggle\Entity\Toggle;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GetGroupTogglesForRelease implements Endpoint
{
    /**
     * @var labsGetGroupToggles
     */
    private $getToggles;

    /**
     * GetGroupTogglesForRelease constructor.
     * @param labsGetGroupToggles $getGroupToggles
     */
    public function __construct(labsGetGroupToggles $getGroupToggles)
    {
        $this->getToggles = $getGroupToggles;
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function execute(Request $request)
    {
        $releaseId = $request->get('release');
        if (!isset($releaseId)) {
            return new JsonResponse('Missing Release ID', 400);
        }

        $toggles = $this->getToggles->execute($releaseId);
        $json = [];

        /** @var Toggle $toggle */
        foreach($toggles as $toggle) {
            $json[] = [
                'id' => $toggle->getId(),
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