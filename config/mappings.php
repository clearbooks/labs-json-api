<?php
return [
    DateTimeInterface::class => \DI\object( DateTime::class )
] + include "mappings.mysql.php";