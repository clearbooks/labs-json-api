<?php

use Symfony\Component\HttpFoundation\Request;

return [
    DateTimeInterface::class => new DateTime,
    Request::class => function() {
        return Request::createFromGlobals();
    }
] + (include "mappings.mysql.php")
  + (include "mappings.authentication.php");
