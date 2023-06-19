<?php

declare(strict_types=1);

namespace Clearbooks\LabsApi\Cors;

use Clearbooks\Dilex\Middleware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CorsMiddleware implements Middleware
{
    private AllowedOrigins $allowedOrigins;

    public function __construct(AllowedOrigins $allowedOrigins)
    {
        $this->allowedOrigins = $allowedOrigins;
    }

    public function execute(Request $request, ?Response $response = null)
    {
        if ($response === null) {
            return null;
        }

        if ($request->getMethod() === Request::METHOD_OPTIONS) {
            $response->headers->set('Access-Control-Allow-Methods', 'GET,HEAD,PUT,PATCH,POST,DELETE');
            $response->headers->set('Access-Control-Max-Age', '300');
        }

        $this->setAllowOriginHeader($request, $response);
    }

    private function setAllowOriginHeader(Request $request, Response $response): void
    {
        $origin = $request->headers->get( "Origin" );

        if (!is_string($origin)) {
            return;
        }

        $allowedOrigins = $this->allowedOrigins->getAllowedOrigins();

        if (in_array($origin, $allowedOrigins) || in_array('*', $allowedOrigins)) {
            $response->headers->set('Access-Control-Allow-Origin', $origin);
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
            $response->headers->set('Access-Control-Allow-Headers', 'Authorization');
        }
    }
}
