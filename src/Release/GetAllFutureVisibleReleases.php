<?php
/**
 * Created by PhpStorm.
 * User: dan
 * Date: 28/12/15
 * Time: 13:28
 */

namespace Clearbooks\LabsApi\Release;


use Clearbooks\Dilex\Endpoint;
use Clearbooks\Labs\Release\Gateway\ReleaseGateway;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GetAllFutureVisibleReleases implements Endpoint
{
    /**
     * @var ReleaseGateway
     */
    private $gateway;


    /**
     * GetAllFutureVisibleReleases constructor.
     * @param ReleaseGateway $gateway
     */
    public function __construct(ReleaseGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    public function execute(Request $request)
    {
        $releases = $this->gateway->getAllFutureVisibleReleases();
        $json = [];

        foreach ($releases as $release) {
            $json[] =
                [
                    'id' => $release->getReleaseId(),
                    'date' => $release->getReleaseDate()->format('Y-m-d')
                ];
        }
        return new JsonResponse($json);

    }
}