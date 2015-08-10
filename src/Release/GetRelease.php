<?php
namespace CLearbooks\LabsApi\Release;
use Symfony\Component\HttpFoundation\JsonResponse;
use Silex\Application;

class GetRelease
{
    public function execute()
    {
        return new JsonResponse( [
            [
                'name' => 'A'
            ],
            [
                'name' => 'B'
            ]
        ] );
    }
}