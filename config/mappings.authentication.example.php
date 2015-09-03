<?php
use Emarref\Jwt\Algorithm\AlgorithmInterface;
use Emarref\Jwt\Algorithm\Hs512;

return [
    AlgorithmInterface::class => new Hs512("{{ encryption_secret_key }}")
];
