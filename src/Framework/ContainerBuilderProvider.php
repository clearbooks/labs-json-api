<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 01/09/15
 * Time: 16:40
 */

namespace Clearbooks\LabsApi\Framework;


use DI\ContainerBuilder;
use Silex\Application;
use Silex\ServiceProviderInterface;

class ContainerBuilderProvider implements ServiceProviderInterface
{

    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     * @param Application $app
     */
    public function register(Application $app)
    {
        $cb = new ContainerBuilder();
        $cb->useAutowiring( true );
        $cb->addDefinitions( '../../config/mappings.php' );
        $app['container_builder'] = $cb->build();
    }

    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registered
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     * @param Application $app
     */
    public function boot(Application $app)
    {
    }
}