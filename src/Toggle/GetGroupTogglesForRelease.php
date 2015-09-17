<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 21/08/15
 * Time: 14:58
 */

namespace Clearbooks\LabsApi\Toggle;


use Clearbooks\Labs\Toggle\GetGroupTogglesForRelease as labsGetGroupToggles;
use Clearbooks\Dilex\Endpoint;
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

        foreach($toggles as $toggle) {
            $json[] = [
                'name' => $toggle->getName()
            ];
        }
        return new JsonResponse($json);
    }
}