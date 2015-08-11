<?php
namespace CLearbooks\LabsApi\Release;
use Clearbooks\LabsApi\Framework\Endpoint;
use Symfony\Component\HttpFoundation\JsonResponse;
use Silex\Application;

class GetRelease implements Endpoint
{
    public function execute()
    {
        return new JsonResponse( [] );
    }
}