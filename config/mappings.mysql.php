<?php
use Clearbooks\Labs\Release\Gateway\ReleaseGateway;
use Clearbooks\Labs\Release\Gateway\ReleaseToggleCollection;
use Clearbooks\Labs\Toggle\Gateway\ActivatedToggleGateway;
use Clearbooks\Labs\Toggle\Gateway\UserToggleGateway;
use Clearbooks\Labs\Toggle\Gateway\ActivatableToggleGateway;
use Clearbooks\LabsMysql\Release\MysqlReleaseGateway;
use Clearbooks\LabsMysql\Release\MysqlReleaseToggleCollectionGateway;
use Clearbooks\LabsMysql\Toggle\MysqlUserToggleGateway;
use Clearbooks\LabsMysql\Toggle\MysqlActivatableToggleGateway;
use Clearbooks\Labs\Toggle\Gateway\GroupToggleGateway;
use Clearbooks\LabsMysql\Toggle\MysqlGroupToggleGateway;
use Clearbooks\LabsMysql\Toggle\MySqlActivatedToggleGateway;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

return [
    ReleaseGateway::class => \DI\object( MysqlReleaseGateway::class ),
    ReleaseToggleCollection::class => \DI\object( MysqlReleaseToggleCollectionGateway::class ),
    UserToggleGateway::class => \Di\object(MysqlUserToggleGateway::class),
    ActivatableToggleGateway::class => \DI\object (MysqlActivatableToggleGateway::class),
    GroupToggleGateway::class => \Di\object(MysqlGroupToggleGateway::class),
    ActivatedToggleGateway::class => \Di\object(MysqlActivatedToggleGateway::class),

    Connection::class => function() {
        return DriverManager::getConnection([
            'dbname' => '{{ labs_db_name }}',
            'user' => '{{ labs_db_user }}',
            'password' => '{{ labs_db_pass }}',
            'host' => '{{ labs_db_host }}',
            'driver' => 'pdo_mysql',
        ] );
    }
];
