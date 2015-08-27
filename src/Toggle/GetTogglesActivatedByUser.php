<?php
/**
 * Created by PhpStorm.
 * User: dan
 * Date: 27/08/15
 * Time: 09:52
 */

namespace Clearbooks\LabsApi\Toggle;


use Clearbooks\Labs\Toggle\GetActivatedToggles;
use Clearbooks\LabsApi\Framework\Endpoint;
use Clearbooks\LabsMysql\Toggle\Entity\Toggle;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GetTogglesActivatedByUser implements Endpoint
{
    /**
     * @var GetActivatedToggles
     */
    private $getActivatedToggles;

    /**
     * GetTogglesActivatedByUser constructor.
     * @param GetActivatedToggles $getActivatedToggles
     */
    public function __construct(GetActivatedToggles $getActivatedToggles)
    {
        $this->getActivatedToggles = $getActivatedToggles;
    }

    public function execute(Request $request)
    {
        $userId = $request->get('userId');
        if(!isset($userId)) {
            return new JsonResponse('Missing user identifier', 400);
        }

        $activatedToggles = $this->getActivatedToggles->execute($userId);
        $json = [];
        /**
         * @var Toggle $toggle
         */
        foreach($activatedToggles as $toggle) {
            $json[] = [
                'name' => $toggle->getName(),
                'release' => $toggle->getRelease()
            ];
        }

        return new JsonResponse($json);
    }
}