<?php
namespace Clearbooks\LabsApi\Authentication;
use Clearbooks\LabsApi\Framework\Middleware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class JwtGuard implements Middleware
{
    public function execute( Request $request )
    {
        //dan!
    }
}