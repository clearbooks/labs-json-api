<?php
use Clearbooks\LabsApi\Toggle\GetIsToggleActive;
use Clearbooks\LabsApi\Toggle\GetGroupTogglesForRelease;
use Clearbooks\LabsApi\Toggle\GetToggles;

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
 *  description="Operations about toggles"
 * )
 */
require_once "../../vendor/autoload.php";
$app = new \Silex\Application();
$app['debug'] = true;

$cb = new \DI\ContainerBuilder();
$cb->useAutowiring( true );

$cb->addDefinitions( '../../config/mappings.php' );
$app['resolver'] = $app->share(function () use ( $app, $cb ) {
    return new \Clearbooks\LabsApi\Framework\ControllerResolver( $app, $cb->build() );
});

/**
 * @SWG\Get(
 *  path="/toggle/list",
 *  summary="Get a list of toggles",
 *  produces={"application/json"},
 *  tags={"toggles"},
 *  @SWG\Response(
 *   response=200,
 *   description="List of toggles"
 *  )
 * )
 */
$app->get( 'toggle/list', GetToggles::class );
$app->get( 'toggle/is-active', GetIsToggleActive::class);r
$app->get( 'toggle/group/list', GetGroupTogglesForRelease::class);

$app->run();
