<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 21/08/15
 * Time: 14:58
 */

namespace Clearbooks\LabsApi\Toggle;


use Clearbooks\LabsApi\Framework\Endpoint;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GetGroupTogglesForRelease implements Endpoint
{

    public function execute(Request $r)
    {
        return new JsonResponse('Missing Release ID', 400);
    }
}