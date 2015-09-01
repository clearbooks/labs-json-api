<?php
use Clearbooks\Labs\Release\Gateway\PublicReleaseGateway;
use Clearbooks\Labs\Release\Gateway\ReleaseGateway;
use Clearbooks\Labs\Release\Gateway\ReleaseToggleCollection;
use Clearbooks\Labs\Toggle\Gateway\UserToggleGateway;
use Clearbooks\Labs\Toggle\Gateway\ActivatableToggleGateway;
use Clearbooks\Labs\User\MockPermissionService;
use Clearbooks\Labs\User\UseCase\PermissionService;
use Clearbooks\Labs\User\UseCase\ToggleStatusModifier;
use Clearbooks\Labs\User\ToggleStatusModifier as ToggleStatusModifierImplementation;
use Clearbooks\Labs\User\UseCase\ToggleStatusModifierService;
use Clearbooks\LabsMysql\Release\MysqlPublicReleaseGateway;
use Clearbooks\LabsMysql\Release\MysqlReleaseGateway;
use Clearbooks\LabsMysql\Release\MysqlReleaseToggleCollectionGateway;
use Clearbooks\LabsMysql\Toggle\MysqlUserToggleGateway;
use Clearbooks\LabsMysql\Toggle\MysqlActivatableToggleGateway;
use Clearbooks\Labs\Toggle\Gateway\GroupToggleGateway;
use Clearbooks\LabsMysql\Toggle\MysqlGroupToggleGateway;
use Clearbooks\LabsMysql\User\MysqlToggleStatusModifierService;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

return [
    ReleaseGateway::class => \DI\object( MysqlReleaseGateway::class ),
    ReleaseToggleCollection::class => \DI\object( MysqlReleaseToggleCollectionGateway::class ),
    UserToggleGateway::class => \Di\object(MysqlUserToggleGateway::class),
    ActivatableToggleGateway::class => \DI\object (MysqlActivatableToggleGateway::class),
    GroupToggleGateway::class => \Di\object(MysqlGroupToggleGateway::class),
    ToggleStatusModifier::class => \Di\object(ToggleStatusModifierImplementation::class),
    ToggleStatusModifierService::class => \Di\object(MysqlToggleStatusModifierService::class),
    PermissionService::class => \Di\object(MockPermissionService::class),
    PublicReleaseGateway::class => \Di\object(MysqlPublicReleaseGateway::class),

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