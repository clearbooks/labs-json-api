<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 20/08/15
 * Time: 14:40
 */

namespace Clearbooks\LabsApi\Release;


use Clearbooks\Labs\Release\GetPublicRelease;
use Clearbooks\LabsApi\Framework\Endpoint;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GetAllPublicReleases implements Endpoint
{
    /**
     * @var GetPublicRelease
     */
    private $getReleases;

    /**
     * @param GetPublicRelease $getPublicRelease
     */
    public function __construct( GetPublicRelease $getPublicRelease)
    {
        $this->getReleases = $getPublicRelease;

    }

    public function execute(Request $request)
    {
        $releases = $this->getReleases->execute();
        $json = [];

        foreach($releases as $release) {
            $json[] = [
                'name' => $release->getReleaseName(),
                'date' => $release->getReleaseDate()->format('Y-m-d'),
                'releaseInfoUrl' => $release->getReleaseInfoUrl()
            ];
        }

        return new JsonResponse($json);
    }
}