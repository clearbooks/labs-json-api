<?php
namespace Clearbooks\LabsApi\Framework;
use Symfony\Component\HttpFoundation\Request;

class MiddlewareDummy implements Middleware
{
    /**
     * @param Request $request
     */
    public function execute( Request $request )
    {
        // do nothing.
    }
}