<?php
namespace Clearbooks\LabsApi\Framework;
use Symfony\Component\HttpFoundation\Request;

interface Endpoint
{
    public function execute( Request $request );
}