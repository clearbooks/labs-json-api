<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 02/09/15
 * Time: 10:44
 */
namespace Clearbooks\LabsApi\Framework\Tokens;

interface TokenProviderInterface
{
    public function verifyToken();

    public function getUserId();

    public function getGroupId();
}