<?php
use Clearbooks\LabsApi\Release\GetRelease;
require_once "../vendor/autoload.php";

$app = new \Silex\Application();
$cb = new \DI\ContainerBuilder();
$cb->useAutowiring( true );

$cb->addDefinitions( '../config/mappings.php' );
$app['resolver'] = $app->share(function () use ( $app, $cb ) {
    return new \Clearbooks\LabsApi\Framework\ControllerResolver( $app, $cb->build() );
});

$app->get( 'release/list', GetRelease::class );
$app->run();