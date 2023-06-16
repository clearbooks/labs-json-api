<?php

declare(strict_types=1);

namespace Clearbooks\LabsApi\Status;

use Clearbooks\Dilex\Endpoint;
use Clearbooks\Dilex\JwtGuard\NoJwtRequired;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class StatusController implements Endpoint, NoJwtRequired
{
    public function execute(Request $request)
    {
        return new Response('OK', 200);
    }
}
