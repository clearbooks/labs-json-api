<?php
use Clearbooks\LabsApi\Toggle\GetIsToggleActive;
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
$app->get( 'toggle/is-active', GetIsToggleActive::class);
$app->run();