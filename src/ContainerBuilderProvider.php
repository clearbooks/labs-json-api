<?php
namespace Clearbooks\LabsApi;

use DI\ContainerBuilder;

class ContainerBuilderProvider
{
    public function getCacheDir()
    {
        return __DIR__ . '/../var/php-di/cache';
    }

    public function getContainerBuilder(): ContainerBuilder
    {
        $cb = new ContainerBuilder();
        $cb->addDefinitions( __DIR__ . '/../config/mappings.php' );
        $cb->useAutowiring( true );
        $cb->enableCompilation( $this->getCacheDir() );
        return $cb;
    }
}
