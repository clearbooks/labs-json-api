<?php
namespace Clearbooks\LabsApi\Framework;
use Symfony\Component\HttpFoundation\Request;

class EndpointDummy implements Endpoint
{
    public function execute( Request $request )
    {
        // do nothing.
    }
}