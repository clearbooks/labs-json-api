<?php
use Clearbooks\LabsApi\Release\GetRelease;
require_once "../vendor/autoload.php";
$app = new \Silex\Application();

$app->get( 'release/list', GetRelease::class . '::execute' );
$app->run();