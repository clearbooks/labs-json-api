<?php

declare(strict_types=1);

namespace Clearbooks\LabsApi\Cors;

use Clearbooks\Dilex\Endpoint;
use Clearbooks\Dilex\JwtGuard\NoJwtRequired;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OptionsController implements Endpoint, NoJwtRequired
{
    public function execute(Request $request): Response
    {
        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
