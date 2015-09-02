<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 20/08/15
 * Time: 14:40
 */

namespace Clearbooks\LabsApi\Release;


use Clearbooks\Labs\Release\GetPublicReleases;
use Clearbooks\Labs\Release\Release;
use Clearbooks\LabsApi\Framework\Endpoint;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GetAllPublicReleases implements Endpoint
{
    /**
     * @var GetPublicReleases
     */
    private $getReleases;

    /**
     * @param GetPublicReleases $getPublicRelease
     */
    public function __construct( GetPublicReleases $getPublicRelease)
    {
        $this->getReleases = $getPublicRelease;

    }

    public function execute(Request $request)
    {
        $releases = $this->getReleases->execute();
        $json = [];

        /** @var Release $release */
        foreach($releases as $release) {
            $json[] = [
                'id' => $release->getReleaseId(),
                'name' => $release->getReleaseName(),
                'releaseInfoUrl' => $release->getReleaseInfoUrl(),
                'date' => $release->getReleaseDate()->format('Y-m-d')
            ];
        }

        return new JsonResponse($json);
    }
}