<?php
namespace Clearbooks\LabsApi;

use Clearbooks\Dilex\Dilex;
use Clearbooks\Dilex\JwtGuard;
use Clearbooks\Dilex\Route;
use Clearbooks\LabsApi\Cors\CorsMiddleware;
use Clearbooks\LabsApi\Cors\OptionsController;
use Clearbooks\LabsApi\Feedback\AddFeedbackForToggle;
use Clearbooks\LabsApi\Group\GroupToggleStatusModifier;
use Clearbooks\LabsApi\Release\GetAllPublicReleases;
use Clearbooks\LabsApi\Status\StatusController;
use Clearbooks\LabsApi\Toggle\GetAllGroupTogglesVisibleWithoutRelease;
use Clearbooks\LabsApi\Toggle\GetAllToggleStatusForUser;
use Clearbooks\LabsApi\Toggle\GetAllUserTogglesVisibleWithoutRelease;
use Clearbooks\LabsApi\Toggle\GetGroupTogglesForRelease;
use Clearbooks\LabsApi\Toggle\GetIsToggleActive;
use Clearbooks\LabsApi\Toggle\GetTogglesActivatedByUser;
use Clearbooks\LabsApi\Toggle\GetTogglesForRelease;
use Clearbooks\LabsApi\Toggle\GetUserTogglesForRelease;
use Clearbooks\LabsApi\User\IsUserAutoSubscribed;
use Clearbooks\LabsApi\User\UserToggleAutoSubscribe;
use Clearbooks\LabsApi\User\UserToggleStatusModifier;

/**
 * Swagger api information
 *
 * @SWG\Info(
 *  title="Labs json api",
 *  description="An api for labs",
 *  version="Early"
 * )
 * @SWG\Tag(
 *  name="toggles",
 *  description="Operations involving toggles"
 * )
 * @SWG\Tag(
 *  name="releases",
 *  description="Operations involving releases"
 * )
 */
class ApplicationInitializer
{
    /**
     * @var ContainerBuilderProvider
     */
    private $containerBuilderProvider;

    /**
     * @var Dilex|null
     */
    private $dilex = null;

    public function __construct( ContainerBuilderProvider $containerBuilderProvider )
    {
        $this->containerBuilderProvider = $containerBuilderProvider;
    }

    private function loadMiddleware(): void
    {
        $this->dilex->before( JwtGuard::class );
        $this->dilex->after(CorsMiddleware::class);
    }

    private function loadRoutes(): void
    {
        /**
         * @SWG\Get(
         *  path="/public-releases/list",
         *  summary="Get a list of the public releases",
         *  produces={"application/json"},
         *  tags={"releases"},
         *  @SWG\Response(
         *   response=200,
         *   description="List of public releases"
         *  )
         * )
         */
        $this->cors($this->dilex->get( 'public-releases/list', GetAllPublicReleases::class ));

        /**
         * @SWG\Get(
         *  path="/toggle/list",
         *  summary="Get a list of toggles for a particular release",
         *  produces={"application/json"},
         *  tags={"toggles"},
         *  @SWG\Response(
         *   response=200,
         *   description="List of toggles"
         *  ),
         *  @SWG\Parameter(
         *   name="release",
         *   description="The id of the release to get the toggles for",
         *   in="query",
         *   required=true,
         *   type="string"
         *  )
         * )
         */
        $this->cors($this->dilex->get( 'toggle/list', GetTogglesForRelease::class ));

        /**
         * @SWG\Get(
         *  path="/toggle/is-active",
         *  summary="Find out if a toggle is active",
         *  produces={"application/json"},
         *  tags={"toggles"},
         *  @SWG\Response(
         *   response=200,
         *   description="The name of the toggle and if it is active"
         *  ),
         *  @SWG\Response(
         *   response=400,
         *   description="You didn't specify a toggle name."
         *  ),
         *  @SWG\Parameter(
         *   name="name",
         *   description="The name of the toggle to check",
         *   in="query",
         *   required=true,
         *   type="string"
         *  )
         * )
         */
        $this->cors($this->dilex->get( 'toggle/is-active', GetIsToggleActive::class ));

        /**
         * @SWG\Get(
         *  path="/toggle/user/list",
         *  summary="Get all user toggles for a given release",
         *  produces={"application/json"},
         *  tags={"toggles", "releases"},
         *  @SWG\Response(
         *   response=200,
         *   description="A list of the toggle names for the specified release"
         *  ),
         *  @SWG\Response(
         *   response=400,
         *   description="You didn't specify a release ID"
         *  ),
         *  @SWG\Parameter(
         *   name="release",
         *   description="The release ID to get toggles for",
         *   in="query",
         *   required=true,
         *   type="string"
         *  )
         * )
         */
        $this->cors($this->dilex->get( 'toggle/user/list', GetUserTogglesForRelease::class ));

        /**
         * @SWG\Get(
         *  path="/toggle/user/is-activated",
         *  summary="List all toggles that have been activated by a specified user",
         *  produces={"application/json"},
         *  tags={"toggles"},
         *  @SWG\Response(
         *   response=200,
         *   description="A list of activated toggles"
         *  ),
         *  @SWG\Parameter(
         *   name="release",
         *   description="The id of the user to get the toggles for",
         *   in="query",
         *   required=true,
         *   type="string"
         *  )
         * )
         */
        $this->cors($this->dilex->get( 'toggle/user/is-activated', GetTogglesActivatedByUser::class ));

        /**
         * @SWG\Get(
         *  path="/toggle/user/all-toggle-status",
         *  summary="List status for all toggles, including if a toggle is active or locked for the current login",
         *  produces={"application/json"},
         *  tags={"toggles"},
         *  @SWG\Response(
         *   response=200,
         *   description="A list of activated toggles"
         *  )
         * )
         */
        $this->cors($this->dilex->get( 'toggle/user/all-toggle-status', GetAllToggleStatusForUser::class ));

        /**
         * @SWG\Get(
         *  path="/toggle/group/list",
         *  summary="List all group toggles for a particular release",
         *  produces={"application/json"},
         *  tags={"toggles", "releases"},
         *  @SWG\Response(
         *   response=200,
         *   description="A list of group toggles"
         *  ),
         *  @SWG\Parameter(
         *   name="userId",
         *   description="The id of the release to get the toggles for",
         *   in="query",
         *   required=true,
         *   type="string"
         *  )
         * )
         */
        $this->cors($this->dilex->get( 'toggle/group/list', GetGroupTogglesForRelease::class ));

        /**
         * @SWG\Get(
         *  path="/toggle/user/list-without-release",
         *  summary="Get all user toggles visible without release",
         *  produces={"application/json"},
         *  tags={"toggles"},
         *  @SWG\Response(
         *   response=200,
         *   description="A list of the toggles without release"
         *  )
         * )
         */
        $this->cors($this->dilex->get( 'toggle/user/list-without-release', GetAllUserTogglesVisibleWithoutRelease::class ));

        /**
         * @SWG\Get(
         *  path="/toggle/group/list-without-release",
         *  summary="Get all group toggles visible without release",
         *  produces={"application/json"},
         *  tags={"toggles"},
         *  @SWG\Response(
         *   response=200,
         *   description="A list of the toggles without release"
         *  )
         * )
         */
        $this->cors($this->dilex->get( 'toggle/group/list-without-release', GetAllGroupTogglesVisibleWithoutRelease::class ));

        $this->cors($this->dilex->post('user/toggle/change-status', UserToggleStatusModifier::class));

        $this->cors($this->dilex->get( 'user/is-auto-subscribed', IsUserAutoSubscribed::class ));

        $this->cors($this->dilex->post('user/toggle-auto-subscribe', UserToggleAutoSubscribe::class));

        $this->cors($this->dilex->post('group/toggle/change-status', GroupToggleStatusModifier::class));

        $this->cors($this->dilex->post( 'feedback/give', AddFeedbackForToggle::class ));

        $this->dilex->get('status', StatusController::class);
    }

    public function init(): Dilex
    {
        if ( $this->dilex ) {
            return $this->dilex;
        }

        $container = $this->containerBuilderProvider->getContainerBuilder()->build();
        $this->dilex = new Dilex( 'production', false, $container );
        $this->dilex->setProjectDirectory( __DIR__ . '/../var/symfony/' );
        $this->dilex->setCacheDirectory( "cache" );
        $this->dilex->setLogDirectory( "log" );

        $this->loadMiddleware();
        $this->loadRoutes();

        return $this->dilex;
    }

    private function cors(Route $route): Route
    {
        $this->dilex->options($route->getPath(), OptionsController::class);

        return $route;
    }
}
