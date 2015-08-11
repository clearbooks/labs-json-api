<?php
use Clearbooks\Labs\Release\Gateway\DummyReleaseGateway;
use Clearbooks\Labs\Release\Gateway\ReleaseGateway;

return [
    ReleaseGateway::class => \DI\object( DummyReleaseGateway::class ),
    DateTimeInterface::class => \DI\object( DateTime::class )
];