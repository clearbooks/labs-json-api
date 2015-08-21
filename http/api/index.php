<?php
use Clearbooks\LabsApi\Release\GetRelease;
require_once "../../vendor/autoload.php";

/**
 * Swagger api information
 *
 * @SWG\Info(
 *  title="Labs json api",
 *  description="An api for labs",
 *  version="Early"
 * )
 */

/**
 * @SWG\Tag(
 *  name="release",
 *  description="Operations about releases"
 * )
 */

$app = new \Silex\Application();
$cb = new \DI\ContainerBuilder();
$cb->useAutowiring( true );

$cb->addDefinitions( '../../config/mappings.php' );
$app['resolver'] = $app->share(function () use ( $app, $cb ) {
    return new \Clearbooks\LabsApi\Framework\ControllerResolver( $app, $cb->build() );
});


$app->get( 'release/list', GetRelease::class );

$app->run();