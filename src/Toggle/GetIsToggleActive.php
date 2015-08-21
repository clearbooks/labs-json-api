<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 21/08/15
 * Time: 10:06
 */

namespace Clearbooks\LabsApi\Toggle;

use Clearbooks\Labs\Toggle\IsToggleActive;
use Clearbooks\LabsApi\Framework\Endpoint;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GetIsToggleActive implements Endpoint
{
    /**
     * @var IsToggleActive
     */
    private $getIsActive;

    /**
     * GetIsToggleActive constructor.
     * @param IsToggleActive $getIsActive
     */
    public function __construct(IsToggleActive $getIsActive)
    {
        $this->getIsActive = $getIsActive;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function execute(Request $request)
    {
        if(!$toggleName = $request->get('name')) {
            return new JsonResponse('Missing toggle name', 400);
        }

        $isToggleActive = $this->getIsActive->isToggleActive($toggleName);

        $json = [
            'name' => $toggleName,
            'isActive' => $isToggleActive
        ];

        return new JsonResponse($json);
    }
}