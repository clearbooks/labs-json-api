<?php
use Clearbooks\LabsApi\Release\GetAllPublicReleases;
use Clearbooks\LabsApi\Toggle\GetToggles;

require_once "../../vendor/autoload.php";
$app = new \Silex\Application();
$app['debug'] = true;

$cb = new \DI\ContainerBuilder();
$cb->useAutowiring( true );

$cb->addDefinitions( '../../config/mappings.php' );
$app['resolver'] = $app->share(function () use ( $app, $cb ) {
    return new \Clearbooks\LabsApi\Framework\ControllerResolver( $app, $cb->build() );
});

$app->get( 'toggle/list', GetToggles::class );
$app->get( 'public-releases/list', GetAllPublicReleases::class);
$app->run();