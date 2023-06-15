<?php

return [
    \Firebase\JWT\Key::class => fn () => new \Firebase\JWT\Key("{{ encryption_secret_key }}", "HS512"),
    \Clearbooks\Dilex\JwtGuard\RequestAuthoriser::class => \Di\autowire(\Clearbooks\Dilex\JwtGuard\JwtTokenAuthenticator::class),
    \Clearbooks\Dilex\JwtGuard\IdentityProvider::class => \Di\get(\Clearbooks\Dilex\JwtGuard\RequestAuthoriser::class),
    \Clearbooks\Dilex\JwtGuard\AppIdProvider::class => function() {
        return new \Clearbooks\Dilex\JwtGuard\StaticAppIdProvider(["{{ labs_app_id }}"]);
    }
];
