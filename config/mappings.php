<?php
use Emarref\Jwt\Algorithm\AlgorithmInterface;
use Emarref\Jwt\Algorithm\Hs512;
use Emarref\Jwt\Jwt;

return [
    DateTimeInterface::class => new DateTime,
    AlgorithmInterface::class => new Hs512("{{ encryption_secret_key }}"),
    Jwt::class => new JWt()
] + include "mappings.mysql.php";
