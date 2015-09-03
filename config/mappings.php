<?php
use Emarref\Jwt\Jwt;

return [
    DateTimeInterface::class => new DateTime,
    Jwt::class => new Jwt()
] + (include "mappings.mysql.php")
  + (include "mappings.authentication.php");
