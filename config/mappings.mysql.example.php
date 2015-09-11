<?php
use Clearbooks\Labs\AutoSubscribe\Gateway\AutoSubscriptionProvider;
use Clearbooks\Labs\AutoSubscribe\UseCase\AutoSubscriber;
use Clearbooks\Labs\AutoSubscribe\UserAutoSubscriber;
use Clearbooks\Labs\Client\Toggle\Entity\User as ToggleUserEntity;
use Clearbooks\Labs\Client\Toggle\Entity\Group as ToggleGroupEntity;
use Clearbooks\Labs\AutoSubscribe\Entity\User as AutoSubscribeUser;
use Clearbooks\Labs\Client\Toggle\Entity\Group as LabsGroup;
use Clearbooks\Labs\Client\Toggle\Entity\User as LabsUser;
use Clearbooks\Labs\Client\Toggle\Gateway\GroupTogglePolicyGateway;
use Clearbooks\Labs\Client\Toggle\Gateway\UserTogglePolicyGateway;
use Clearbooks\Labs\Client\Toggle\UseCase\IsToggleActive;
use Clearbooks\Labs\Client\Toggle\ToggleChecker;
use Clearbooks\Labs\Db\Service\ToggleStorage;
use Clearbooks\Labs\Release\Gateway\PublicReleaseGateway;
use Clearbooks\Labs\Release\Gateway\ReleaseGateway;
use Clearbooks\Labs\Release\Gateway\ReleaseToggleCollection;
use Clearbooks\Labs\Toggle\Gateway\ActivatableToggleGateway;
use Clearbooks\Labs\Toggle\Gateway\ActivatedToggleGateway;
use Clearbooks\Labs\Toggle\Gateway\GroupToggleGateway;
use Clearbooks\Labs\Toggle\Gateway\UserToggleGateway;
use Clearbooks\Labs\Toggle\GroupPolicyGateway;
use Clearbooks\Labs\Toggle\ToggleGateway;
use Clearbooks\Labs\Client\Toggle\Gateway\ToggleGateway as ToggleGatewayInterface;
use Clearbooks\Labs\Toggle\UseCase\GroupPolicyRetriever;
use Clearbooks\Labs\Toggle\UseCase\ToggleRetriever;
use Clearbooks\Labs\Toggle\UseCase\UserPolicyRetriever;
use Clearbooks\Labs\Toggle\UserPolicyGateway;
use Clearbooks\Labs\User\MockPermissionService;
use Clearbooks\Labs\User\ToggleStatusModifier as ToggleStatusModifierImplementation;
use Clearbooks\Labs\User\UseCase\PermissionService;
use Clearbooks\Labs\User\UseCase\ToggleStatusModifier;
use Clearbooks\Labs\User\UseCase\ToggleStatusModifierService;
use Clearbooks\LabsApi\Authentication\Tokens\TokenAuthenticationProvider;
use Clearbooks\LabsApi\Authentication\Tokens\TokenProvider;
use Clearbooks\LabsApi\Authentication\Tokens\UserInformationProvider;
use Clearbooks\LabsApi\User\Group;
use Clearbooks\LabsApi\User\User;
use Clearbooks\LabsMysql\Release\MysqlPublicReleaseGateway;
use Clearbooks\LabsMysql\Release\MysqlReleaseGateway;
use Clearbooks\LabsMysql\Release\MysqlReleaseToggleCollectionGateway;
use Clearbooks\LabsMysql\Toggle\MysqlActivatableToggleGateway;
use Clearbooks\LabsMysql\Toggle\MysqlActivatedToggleGateway;
use Clearbooks\LabsMysql\Toggle\MysqlGetAllTogglesGateway;
use Clearbooks\LabsMysql\Toggle\MysqlGroupToggleGateway;
use Clearbooks\LabsMysql\Toggle\MysqlUserToggleGateway;
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
    TokenAuthenticationProvider::class => \Di\object(TokenProvider::class),
    UserInformationProvider::class => \Di\object(TokenProvider::class),
    ActivatedToggleGateway::class => \Di\object(MysqlActivatedToggleGateway::class),
    IsToggleActive::class => \Di\object(ToggleChecker::class),
    ToggleUserEntity::class => \Di\object(User::class),
    ToggleGroupEntity::class=> \Di\object(Group::class),
    UserTogglePolicyGateway::class => \Di\object( UserPolicyGateway::class ),
    GroupTogglePolicyGateway::class => \Di\object( GroupPolicyGateway::class ),
    \Clearbooks\Labs\Toggle\Gateway\GetAllTogglesGateway::class => \Di\object(MysqlGetAllTogglesGateway::class),
    \Clearbooks\Labs\Client\Toggle\UseCase\ToggleChecker::class => \Di\object(\Clearbooks\Labs\Client\Toggle\StatelessToggleChecker::class),
    LabsUser::class => \Di\object(User::class),
    LabsGroup::class=> \Di\object(Group::class),
    ToggleGatewayInterface::class => \Di\object(ToggleGateway::class),
    ToggleRetriever::class => \Di\object(ToggleStorage::class),
    UserPolicyRetriever::class => \Di\object(ToggleStorage::class),
    GroupPolicyRetriever::class => \Di\object(ToggleStorage::class),
    AutoSubscriber::class => \Di\object(UserAutoSubscriber::class),
    AutoSubscribeUser::class => \Di\object(User::class),
    AutoSubscriptionProvider::class => \Di\object(),
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
