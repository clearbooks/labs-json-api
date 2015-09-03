<?php
use Emarref\Jwt\Algorithm\AlgorithmInterface;
use Emarref\Jwt\Algorithm\Hs512;
use Emarref\Jwt\Jwt;

return [
    Jwt::class => new Jwt(),
    AlgorithmInterface::class => new Hs512("{{ encryption_secret_key }}")
];
