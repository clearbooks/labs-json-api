<?php
use Clearbooks\Labs\AutoSubscribe\Entity\User as AutoSubscribeUser;
use Clearbooks\Labs\AutoSubscribe\Gateway\AutoSubscriptionProvider;
use Clearbooks\Labs\AutoSubscribe\UseCase\AutoSubscriber;
use Clearbooks\Labs\AutoSubscribe\UserAutoSubscriber;
use Clearbooks\Labs\Client\Toggle\CanDefaultToggleStatusBeOverruled;
use Clearbooks\Labs\Client\Toggle\Entity\Group as ToggleGroupEntity;
use Clearbooks\Labs\Client\Toggle\Entity\User as ToggleUserEntity;
use Clearbooks\Labs\Client\Toggle\Gateway\AutoSubscribersGateway as IAutoSubscribersGateway;
use Clearbooks\Labs\Client\Toggle\Gateway\GroupTogglePolicyGateway;
use Clearbooks\Labs\Client\Toggle\Gateway\SegmentTogglePolicyGateway;
use Clearbooks\Labs\Client\Toggle\Gateway\ToggleGateway as ToggleGatewayInterface;
use Clearbooks\Labs\Client\Toggle\Gateway\UserTogglePolicyGateway;
use Clearbooks\Labs\Client\Toggle\StatelessToggleChecker;
use Clearbooks\Labs\Client\Toggle\UseCase\CanDefaultToggleStatusBeOverruled as ICanDefaultToggleStatusBeOverruled;
use Clearbooks\Labs\Client\Toggle\UseCase\ToggleChecker;
use Clearbooks\Labs\DateTime\CurrentDateTimeProvider;
use Clearbooks\Labs\DateTime\UseCase\DateTimeProvider;
use Clearbooks\Labs\Db\Service\AutoSubscribersStorage;
use Clearbooks\Labs\Db\Service\ReleaseStorage;
use Clearbooks\Labs\Db\Service\ToggleStorage;
use Clearbooks\Labs\Feedback\AddFeedbackForToggle;
use Clearbooks\Labs\Feedback\Gateway\InsertFeedbackForToggleGateway;
use Clearbooks\Labs\Feedback\UseCase\AddFeedbackForToggle as IAddFeedbackForToggle;
use Clearbooks\Labs\Release\Gateway\PublicReleaseGateway;
use Clearbooks\Labs\Release\Gateway\ReleaseGateway;
use Clearbooks\Labs\Release\Gateway\ReleaseToggleCollection;
use Clearbooks\Labs\Toggle\AutoSubscribersGateway;
use Clearbooks\Labs\Toggle\Gateway\ActivatableToggleGateway;
use Clearbooks\Labs\Toggle\Gateway\GetAllTogglesGateway;
use Clearbooks\Labs\Toggle\Gateway\GetGroupTogglesVisibleWithoutReleaseGateway;
use Clearbooks\Labs\Toggle\Gateway\GetUserTogglesVisibleWithoutReleaseGateway;
use Clearbooks\Labs\Toggle\Gateway\GroupToggleGateway;
use Clearbooks\Labs\Toggle\Gateway\UserToggleGateway;
use Clearbooks\Labs\Toggle\GetGroupTogglesVisibleWithoutRelease;
use Clearbooks\Labs\Toggle\GetUserTogglesVisibleWithoutRelease;
use Clearbooks\Labs\Toggle\UseCase\GetAllToggleStatus as IGetAllToggleStatus;
use Clearbooks\Labs\Toggle\GetAllToggleStatus;
use Clearbooks\Labs\Toggle\GroupPolicyGateway;
use Clearbooks\Labs\Toggle\SegmentPolicyGateway;
use Clearbooks\Labs\Toggle\ToggleGateway;
use Clearbooks\Labs\Toggle\UseCase\GetUserTogglesVisibleWithoutRelease as IGetUserTogglesVisibleWithoutRelease;
use Clearbooks\Labs\Toggle\UseCase\GetGroupTogglesVisibleWithoutRelease as IGetGroupTogglesVisibleWithoutRelease;
use Clearbooks\Labs\Toggle\UseCase\GroupPolicyRetriever;
use Clearbooks\Labs\Toggle\UseCase\ReleaseRetriever;
use Clearbooks\Labs\Toggle\UseCase\SegmentPolicyRetriever;
use Clearbooks\Labs\Toggle\UseCase\ToggleRetriever;
use Clearbooks\Labs\Toggle\UseCase\UserAutoSubscriptionChecker;
use Clearbooks\Labs\Toggle\UseCase\UserPolicyRetriever;
use Clearbooks\Labs\Toggle\UserPolicyGateway;
use Clearbooks\Labs\User\ToggleStatusModifier as ToggleStatusModifierImplementation;
use Clearbooks\Labs\User\UseCase\PermissionService;
use Clearbooks\Labs\User\UseCase\ToggleStatusModifier;
use Clearbooks\Labs\User\UseCase\ToggleStatusModifierService;
use Clearbooks\LabsApi\Group\GroupToggleModifierPermissionService;
use Clearbooks\LabsApi\User\Group;
use Clearbooks\LabsApi\User\User;
use Clearbooks\LabsMysql\AutoSubscribe\MysqlAutoSubscriptionProvider;
use Clearbooks\LabsMysql\Feedback\MysqlInsertFeedbackForToggleGateway;
use Clearbooks\LabsMysql\Release\MysqlPublicReleaseGateway;
use Clearbooks\LabsMysql\Release\MysqlReleaseGateway;
use Clearbooks\LabsMysql\Release\MysqlReleaseToggleCollectionGateway;
use Clearbooks\LabsMysql\Toggle\MysqlActivatableToggleGateway;
use Clearbooks\LabsMysql\Toggle\MysqlGetAllTogglesGateway;
use Clearbooks\LabsMysql\Toggle\MysqlGetTogglesVisibleWithoutReleaseGateway;
use Clearbooks\LabsMysql\Toggle\MysqlGroupToggleGateway;
use Clearbooks\LabsMysql\Toggle\MysqlUserToggleGateway;
use Clearbooks\LabsMysql\User\MysqlToggleStatusModifierService;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

return [
    ReleaseGateway::class => \DI\autowire( MysqlReleaseGateway::class ),
    ReleaseToggleCollection::class => \DI\autowire( MysqlReleaseToggleCollectionGateway::class ),
    UserToggleGateway::class => \Di\autowire(MysqlUserToggleGateway::class),
    ActivatableToggleGateway::class => \DI\autowire (MysqlActivatableToggleGateway::class),
    GroupToggleGateway::class => \Di\autowire(MysqlGroupToggleGateway::class),
    ToggleStatusModifier::class => \Di\autowire(ToggleStatusModifierImplementation::class),
    ToggleStatusModifierService::class => \Di\autowire(MysqlToggleStatusModifierService::class),
    PermissionService::class => \Di\autowire(GroupToggleModifierPermissionService::class),
    PublicReleaseGateway::class => \Di\autowire(MysqlPublicReleaseGateway::class),
    ToggleUserEntity::class => \Di\autowire(User::class),
    ToggleGroupEntity::class=> \Di\autowire(Group::class),
    UserTogglePolicyGateway::class => \Di\autowire( UserPolicyGateway::class ),
    GroupTogglePolicyGateway::class => \Di\autowire( GroupPolicyGateway::class ),
    GetAllTogglesGateway::class => \Di\autowire(MysqlGetAllTogglesGateway::class),
    ToggleChecker::class => \Di\autowire(StatelessToggleChecker::class),
    ToggleGatewayInterface::class => \Di\autowire(ToggleGateway::class),
    ToggleRetriever::class => \Di\autowire(ToggleStorage::class),
    UserPolicyRetriever::class => \Di\autowire(ToggleStorage::class),
    GroupPolicyRetriever::class => \Di\autowire(ToggleStorage::class),
    AutoSubscriber::class => \Di\autowire(UserAutoSubscriber::class),
    AutoSubscribeUser::class => \Di\autowire(User::class),
    AutoSubscriptionProvider::class => \Di\autowire(MysqlAutoSubscriptionProvider::class),
    IAddFeedbackForToggle::class => \Di\autowire( AddFeedbackForToggle::class ),
    InsertFeedbackForToggleGateway::class => \Di\autowire( MysqlInsertFeedbackForToggleGateway::class ),
    IGetAllToggleStatus::class => \Di\autowire( GetAllToggleStatus::class ),
    ReleaseRetriever::class => \Di\autowire( ReleaseStorage::class ),
    DateTimeProvider::class => \Di\autowire( CurrentDateTimeProvider::class ),
    IAutoSubscribersGateway::class => \Di\autowire( AutoSubscribersGateway::class ),
    UserAutoSubscriptionChecker::class => \Di\autowire( AutoSubscribersStorage::class ),
    SegmentTogglePolicyGateway::class => \Di\autowire( SegmentPolicyGateway::class ),
    SegmentPolicyRetriever::class => \Di\autowire( ToggleStorage::class ),
    ICanDefaultToggleStatusBeOverruled::class => \Di\autowire( CanDefaultToggleStatusBeOverruled::class ),
    GetUserTogglesVisibleWithoutReleaseGateway::class => \Di\autowire( MysqlGetTogglesVisibleWithoutReleaseGateway::class ),
    GetGroupTogglesVisibleWithoutReleaseGateway::class => \Di\autowire( MysqlGetTogglesVisibleWithoutReleaseGateway::class ),
    IGetUserTogglesVisibleWithoutRelease::class => \Di\autowire( GetUserTogglesVisibleWithoutRelease::class ),
    IGetGroupTogglesVisibleWithoutRelease::class => \Di\autowire( GetGroupTogglesVisibleWithoutRelease::class ),
    Connection::class => function () {
        return DriverManager::getConnection( [
            'dbname' => '{{ labs_db_name }}',
            'user' => '{{ labs_db_user }}',
            'password' => '{{ labs_db_pass }}',
            'host' => '{{ labs_db_host }}',
            'driver' => 'pdo_mysql',
        ] );
    }
];
