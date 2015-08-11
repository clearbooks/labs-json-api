<?php
namespace CLearbooks\LabsApi\Release;
use Clearbooks\LabsApi\Framework\Endpoint;
use Symfony\Component\HttpFoundation\JsonResponse;
use Silex\Application;

class GetRelease implements Endpoint
{
    /**
     * @SWG\Get(
     *  path="/release/list",
     *  summary="Get a list of releases",
     *  produces={"application/json"},
     *  tags={"release"},
     *  @SWG\Response(
     *   response=200,
     *   description="List of releases"
     *  )
     * )
     */
    public function execute()
    {
        return new JsonResponse( [] );
    }
}