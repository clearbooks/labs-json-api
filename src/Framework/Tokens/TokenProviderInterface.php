<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 02/09/15
 * Time: 10:44
 */
namespace Clearbooks\LabsApi\Framework\Tokens;

use Symfony\Component\HttpFoundation\Request;

interface TokenProviderInterface
{
    public function setToken(Request $request);

    public function verifyToken();

    public function getUserId();

    public function getGroupId();
}