<?php
use Emarref\Jwt\Algorithm\AlgorithmInterface;
use Emarref\Jwt\Algorithm\Hs512;

return [
    DateTimeInterface::class => new DateTime,
    AlgorithmInterface::class => new Hs512("{{ encryption_secret_key }}")
] + include "mappings.mysql.php";
