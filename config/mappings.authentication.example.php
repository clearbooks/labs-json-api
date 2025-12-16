<?php

return [
    \Clearbooks\Dilex\JwtGuard\RequestAuthoriser::class => \Di\factory(static fn ($c) => new \Clearbooks\Dilex\JwtGuard\JwtTokenAuthenticator(
        key: new \Firebase\JWT\Key("{{ encryption_secret_key }}", "HS512"),
        appIdProvider: $c->get(\Clearbooks\Dilex\JwtGuard\AppIdProvider::class)
    )),
    \Clearbooks\Dilex\JwtGuard\IdentityProvider::class => \Di\get(\Clearbooks\Dilex\JwtGuard\RequestAuthoriser::class),
    \Clearbooks\Dilex\JwtGuard\AppIdProvider::class => fn() => new \Clearbooks\Dilex\JwtGuard\StaticAppIdProvider(["{{ labs_app_id }}"]),
    \Clearbooks\LabsApi\Cors\AllowedOrigins::class => fn () => new \Clearbooks\LabsApi\Cors\AllowedOrigins(['{{ cors_allowed_origin }}'])
];
