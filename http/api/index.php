<?php

use Clearbooks\LabsApi\ApplicationInitializer;
use Clearbooks\LabsApi\ContainerBuilderProvider;

require_once "../../vendor/autoload.php";

$app = (new ApplicationInitializer(new ContainerBuilderProvider()))->init();
$app->run();
