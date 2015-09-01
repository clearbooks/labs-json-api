<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 01/09/15
 * Time: 14:17
 */

namespace Clearbooks\LabsApi\Framework;


use Clearbooks\LabsApi\Framework\Tokens\TokenProvider;
use Emarref\Jwt\Algorithm\AlgorithmInterface;
use Emarref\Jwt\Algorithm\Hs512;
use Emarref\Jwt\Jwt;
use Emarref\Jwt\Token;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AuthenticationProvider implements ServiceProviderInterface
{

    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     * @param Application $app
     */
    public function register(Application $app)
    {
        $app['authenticate_token'] = $app->protect(function(Request $request) use ($app) {
            $algorithm = $app['container_builder']->get(AlgorithmInterface::class);
            $tokenAuthenticator = new TokenProvider($request, new Jwt(), $algorithm);

            if(!$algorithm instanceof Hs512) {
                return new JsonResponse("Algorithm was not Hs512", 403);
            }
            if(!$tokenAuthenticator->verifyToken()) {
                return new JsonResponse("Couldn't verify token", 403);
            }
            return null;
        });
    }

    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registered
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     * @param Application $app
     */
    public function boot(Application $app)
    {
    }
}