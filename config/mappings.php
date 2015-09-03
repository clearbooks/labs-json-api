<?php

return [
    DateTimeInterface::class => new DateTime
] + (include "mappings.mysql.php")
  + (include "mappings.authentication.php");
