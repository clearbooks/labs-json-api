<?php
use Clearbooks\Labs\Release\Gateway\ReleaseGateway;
use Clearbooks\LabsMysql\Release\MysqlReleaseGateway;
use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\DriverManager;

return [
    ReleaseGateway::class => \DI\object( MysqlReleaseGateway::class ),
    Connection::class => function() {
        DriverManager::getConnection([
            'dbname' => '{{ labs_db_name }}',
            'user' => '{{ labs_db_user }}',
            'password' => '{{ labs_db_pass }}',
            'host' => '{{ labs_db_host }}',
            'driver' => 'pdo_mysql',
        ] );
    }
];