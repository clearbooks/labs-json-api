<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 07/09/15
 * Time: 10:48
 */

namespace Clearbooks\LabsApi\Framework\Authentication\Token;


use Symfony\Component\HttpFoundation\Request;

class MockTokenRequest extends Request
{

    /**
     * MockTokenRequest constructor.
     * @param $serialisedToken
     */
    public function __construct($serialisedToken)
    {
        parent::__construct();
        $this->headers->set('Authorization', $serialisedToken);
    }
}