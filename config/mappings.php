<?php
use Emarref\Jwt\Algorithm\AlgorithmInterface;
use Emarref\Jwt\Algorithm\None;

return [
    DateTimeInterface::class => new DateTime,
    AlgorithmInterface::class => new None
] + include "mappings.mysql.php";