<?php
use Emarref\Jwt\Algorithm\AlgorithmInterface;
use Emarref\Jwt\Algorithm\Hs512;
use Emarref\Jwt\Jwt;

return [
    Jwt::class => new Jwt(),
    AlgorithmInterface::class => new Hs512("{{ encryption_secret_key }}"),
    \Clearbooks\Dilex\JwtGuard\RequestAuthoriser::class => \Di\autowire(\Clearbooks\Dilex\JwtGuard\JwtTokenAuthenticator::class),
    \Clearbooks\Dilex\JwtGuard\IdentityProvider::class => \Di\get(\Clearbooks\Dilex\JwtGuard\RequestAuthoriser::class),
    \Clearbooks\Dilex\JwtGuard\AppIdProvider::class => function() {
        return new \Clearbooks\Dilex\JwtGuard\StaticAppIdProvider(["{{ labs_app_id }}"]);
    }
];
