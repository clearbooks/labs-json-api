<?php

use Clearbooks\LabsApi\ApplicationInitializer;
use Clearbooks\LabsApi\ContainerBuilderProvider;
use Symfony\Component\Filesystem\Filesystem;

require_once __DIR__ . "/../vendor/autoload.php";

$fileSystem = new Filesystem();
$containerBuilderProvider = new ContainerBuilderProvider();
$fileSystem->remove( $containerBuilderProvider->getCacheDir() );
$app = (new ApplicationInitializer($containerBuilderProvider))->init();
$fileSystem->remove( $app->getCacheDir() );
$app->boot();
