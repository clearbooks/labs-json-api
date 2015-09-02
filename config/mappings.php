<?php
use Clearbooks\LabsApi\Framework\BaseUrl\BaseUrlProvider;
use Clearbooks\LabsApi\Framework\BaseUrl\StaticBaseUrlProvider;

return [
    DateTimeInterface::class => new DateTime,
    BaseUrlProvider::class => new StaticBaseUrlProvider( '{{ labs_base_url }}' )

] + include "mappings.mysql.php";