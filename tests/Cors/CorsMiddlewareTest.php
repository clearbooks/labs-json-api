<?php

declare(strict_types=1);

namespace Clearbooks\LabsApi\Cors;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CorsMiddlewareTest extends TestCase
{
    /** @test */
    public function GivenNoAllowedOrigins_WhenOptionsRequestHandled_NoAllowedOriginHeaderSet(): void
    {
        $middleware = new CorsMiddleware(new AllowedOrigins([]));
        $response = new Response();

        $middleware->execute($this->requestWithOrigin(Request::METHOD_OPTIONS, 'https://example.com'), $response);

        self::assertNull($response->headers->get('Access-Control-Allow-Origin'));
        self::assertNull($response->headers->get('Access-Control-Allow-Credentials'));
    }

    /** @test */
    public function GivenNoAllowedOrigins_WhenOptionsRequestHandled_AllowedMethodsHeaderSet(): void
    {
        $middleware = new CorsMiddleware(new AllowedOrigins([]));
        $response = new Response();

        $middleware->execute($this->requestWithOrigin(Request::METHOD_OPTIONS, 'https://example.com'), $response);

        self::assertEquals('GET,HEAD,PUT,PATCH,POST,DELETE', $response->headers->get('Access-Control-Allow-Methods'));
        self::assertEquals('300', $response->headers->get('Access-Control-Max-Age'));
    }

    /** @test */
    public function GivenAllowedOrigin_WhenOptionsRequestHandledWithoutOrigin_NoAllowedOriginHeaderSet(): void
    {
        $middleware = new CorsMiddleware(new AllowedOrigins(['https://example.net']));
        $response = new Response();

        $middleware->execute($this->requestWithoutOrigin(Request::METHOD_OPTIONS), $response);

        self::assertNull($response->headers->get('Access-Control-Allow-Origin'));
        self::assertNull($response->headers->get('Access-Control-Allow-Credentials'));
    }

    /** @test */
    public function GivenAllowedOrigin_WhenOptionsRequestHandledWithoutOrigin_AllowedMethodsHeaderSet(): void
    {
        $middleware = new CorsMiddleware(new AllowedOrigins(['https://example.net']));
        $response = new Response();

        $middleware->execute($this->requestWithoutOrigin(Request::METHOD_OPTIONS), $response);

        self::assertEquals('GET,HEAD,PUT,PATCH,POST,DELETE', $response->headers->get('Access-Control-Allow-Methods'));
        self::assertEquals('300', $response->headers->get('Access-Control-Max-Age'));
    }

    /** @test */
    public function GivenAllowedOrigin_WhenOptionsRequestHandledFromDisallowedOrigin_NoAllowedOriginHeaderSet(): void
    {
        $middleware = new CorsMiddleware(new AllowedOrigins(['https://example.net']));
        $response = new Response();

        $middleware->execute($this->requestWithOrigin(Request::METHOD_OPTIONS, 'https://example.com'), $response);

        self::assertNull($response->headers->get('Access-Control-Allow-Origin'));
        self::assertNull($response->headers->get('Access-Control-Allow-Credentials'));
    }

    /** @test */
    public function GivenAllowedOrigin_WhenOptionsRequestHandledFromDisallowedOrigin_AllowedMethodsHeaderSet(): void
    {
        $middleware = new CorsMiddleware(new AllowedOrigins(['https://example.net']));
        $response = new Response();

        $middleware->execute($this->requestWithOrigin(Request::METHOD_OPTIONS, 'https://example.com'), $response);

        self::assertEquals('GET,HEAD,PUT,PATCH,POST,DELETE', $response->headers->get('Access-Control-Allow-Methods'));
        self::assertEquals('300', $response->headers->get('Access-Control-Max-Age'));
    }

    /** @test */
    public function GivenAllowedOrigin_WhenOptionsRequestHandledFromAllowedOrigin_AllowedOriginHeaderSet(): void
    {
        $middleware = new CorsMiddleware(new AllowedOrigins(['https://example.net']));
        $response = new Response();

        $middleware->execute($this->requestWithOrigin(Request::METHOD_OPTIONS, 'https://example.net'), $response);

        self::assertEquals('https://example.net', $response->headers->get('Access-Control-Allow-Origin'));
        self::assertEquals('true', $response->headers->get('Access-Control-Allow-Credentials'));
    }

    /** @test */
    public function GivenAllowedOrigin_WhenOptionsRequestHandledFromAllowedOrigin_AllowedMethodsHeaderSet(): void
    {
        $middleware = new CorsMiddleware(new AllowedOrigins(['https://example.net']));
        $response = new Response();

        $middleware->execute($this->requestWithOrigin(Request::METHOD_OPTIONS, 'https://example.net'), $response);

        self::assertEquals('GET,HEAD,PUT,PATCH,POST,DELETE', $response->headers->get('Access-Control-Allow-Methods'));
        self::assertEquals('300', $response->headers->get('Access-Control-Max-Age'));
    }

    /** @test */
    public function GivenWildcardAllowedOrigin_WhenOptionsRequestHandledWithoutOrigin_NoAllowedOriginHeaderSet(): void
    {
        $middleware = new CorsMiddleware(new AllowedOrigins(['*']));
        $response = new Response();

        $middleware->execute($this->requestWithoutOrigin(Request::METHOD_OPTIONS), $response);

        self::assertNull($response->headers->get('Access-Control-Allow-Origin'));
        self::assertNull($response->headers->get('Access-Control-Allow-Credentials'));
    }

    /** @test */
    public function GivenWildcardAllowedOrigin_WhenOptionsRequestHandledWithoutOrigin_AllowedMethodsHeaderSet(): void
    {
        $middleware = new CorsMiddleware(new AllowedOrigins(['*']));
        $response = new Response();

        $middleware->execute($this->requestWithoutOrigin(Request::METHOD_OPTIONS), $response);

        self::assertEquals('GET,HEAD,PUT,PATCH,POST,DELETE', $response->headers->get('Access-Control-Allow-Methods'));
        self::assertEquals('300', $response->headers->get('Access-Control-Max-Age'));
    }

    /** @test */
    public function GivenWildcardAllowedOrigin_WhenOptionsRequestHandledFromAllowedOrigin_AllowedOriginHeaderSet(): void
    {
        $middleware = new CorsMiddleware(new AllowedOrigins(['*']));
        $response = new Response();

        $middleware->execute($this->requestWithOrigin(Request::METHOD_OPTIONS, 'https://example.net'), $response);

        self::assertEquals('https://example.net', $response->headers->get('Access-Control-Allow-Origin'));
        self::assertEquals('true', $response->headers->get('Access-Control-Allow-Credentials'));
    }

    /** @test */
    public function GivenWildcardAllowedOrigin_WhenOptionsRequestHandledFromAllowedOrigin_AllowedMethodsHeaderSet(): void
    {
        $middleware = new CorsMiddleware(new AllowedOrigins(['*']));
        $response = new Response();

        $middleware->execute($this->requestWithOrigin(Request::METHOD_OPTIONS, 'https://example.net'), $response);

        self::assertEquals('GET,HEAD,PUT,PATCH,POST,DELETE', $response->headers->get('Access-Control-Allow-Methods'));
        self::assertEquals('300', $response->headers->get('Access-Control-Max-Age'));
    }

    /** @test */
    public function GivenNoAllowedOrigins_WhenNonOptionsRequestHandled_NoAllowedOriginHeaderSet(): void
    {
        $middleware = new CorsMiddleware(new AllowedOrigins([]));
        $response = new Response();

        $middleware->execute($this->requestWithOrigin(Request::METHOD_POST, 'https://example.com'), $response);

        self::assertNull($response->headers->get('Access-Control-Allow-Origin'));
        self::assertNull($response->headers->get('Access-Control-Allow-Credentials'));
    }

    /** @test */
    public function GivenNoAllowedOrigins_WhenNonOptionsRequestHandled_AllowedMethodsHeaderNotSet(): void
    {
        $middleware = new CorsMiddleware(new AllowedOrigins([]));
        $response = new Response();

        $middleware->execute($this->requestWithOrigin(Request::METHOD_POST, 'https://example.com'), $response);

        self::assertNull($response->headers->get('Access-Control-Allow-Methods'));
        self::assertNull($response->headers->get('Access-Control-Max-Age'));
    }

    /** @test */
    public function GivenAllowedOrigin_WhenNonOptionsRequestHandledWithoutOrigin_NoAllowedOriginHeaderSet(): void
    {
        $middleware = new CorsMiddleware(new AllowedOrigins(['https://example.net']));
        $response = new Response();

        $middleware->execute($this->requestWithoutOrigin(Request::METHOD_POST), $response);

        self::assertNull($response->headers->get('Access-Control-Allow-Origin'));
        self::assertNull($response->headers->get('Access-Control-Allow-Credentials'));
    }

    /** @test */
    public function GivenAllowedOrigin_WhenNonOptionsRequestHandledWithoutOrigin_AllowedMethodsHeaderSet(): void
    {
        $middleware = new CorsMiddleware(new AllowedOrigins(['https://example.net']));
        $response = new Response();

        $middleware->execute($this->requestWithoutOrigin(Request::METHOD_POST), $response);

        self::assertNull($response->headers->get('Access-Control-Allow-Methods'));
        self::assertNull($response->headers->get('Access-Control-Max-Age'));
    }

    /** @test */
    public function GivenAllowedOrigin_WhenNonOptionsRequestHandledFromDisallowedOrigin_NoAllowedOriginHeaderSet(): void
    {
        $middleware = new CorsMiddleware(new AllowedOrigins(['https://example.net']));
        $response = new Response();

        $middleware->execute($this->requestWithOrigin(Request::METHOD_POST, 'https://example.com'), $response);

        self::assertNull($response->headers->get('Access-Control-Allow-Origin'));
        self::assertNull($response->headers->get('Access-Control-Allow-Credentials'));
    }

    /** @test */
    public function GivenAllowedOrigin_WhenNonOptionsRequestHandledFromDisallowedOrigin_AllowedMethodsHeaderSet(): void
    {
        $middleware = new CorsMiddleware(new AllowedOrigins(['https://example.net']));
        $response = new Response();

        $middleware->execute($this->requestWithOrigin(Request::METHOD_POST, 'https://example.com'), $response);

        self::assertNull($response->headers->get('Access-Control-Allow-Methods'));
        self::assertNull($response->headers->get('Access-Control-Max-Age'));
    }

    /** @test */
    public function GivenAllowedOrigin_WhenNonOptionsRequestHandledFromAllowedOrigin_AllowedOriginHeaderSet(): void
    {
        $middleware = new CorsMiddleware(new AllowedOrigins(['https://example.net']));
        $response = new Response();

        $middleware->execute($this->requestWithOrigin(Request::METHOD_POST, 'https://example.net'), $response);

        self::assertEquals('https://example.net', $response->headers->get('Access-Control-Allow-Origin'));
        self::assertEquals('true', $response->headers->get('Access-Control-Allow-Credentials'));
    }

    /** @test */
    public function GivenAllowedOrigin_WhenNonOptionsRequestHandledFromAllowedOrigin_AllowedMethodsHeaderSet(): void
    {
        $middleware = new CorsMiddleware(new AllowedOrigins(['https://example.net']));
        $response = new Response();

        $middleware->execute($this->requestWithOrigin(Request::METHOD_POST, 'https://example.net'), $response);

        self::assertNull($response->headers->get('Access-Control-Allow-Methods'));
        self::assertNull($response->headers->get('Access-Control-Max-Age'));
    }

    /** @test */
    public function GivenWildcardAllowedOrigin_WhenNonOptionsRequestHandledWithoutOrigin_NoAllowedOriginHeaderSet(): void
    {
        $middleware = new CorsMiddleware(new AllowedOrigins(['*']));
        $response = new Response();

        $middleware->execute($this->requestWithoutOrigin(Request::METHOD_POST), $response);

        self::assertNull($response->headers->get('Access-Control-Allow-Origin'));
        self::assertNull($response->headers->get('Access-Control-Allow-Credentials'));
    }

    /** @test */
    public function GivenWildcardAllowedOrigin_WhenNonOptionsRequestHandledWithoutOrigin_AllowedMethodsHeaderSet(): void
    {
        $middleware = new CorsMiddleware(new AllowedOrigins(['*']));
        $response = new Response();

        $middleware->execute($this->requestWithoutOrigin(Request::METHOD_POST), $response);

        self::assertNull($response->headers->get('Access-Control-Allow-Methods'));
        self::assertNull($response->headers->get('Access-Control-Max-Age'));
    }

    /** @test */
    public function GivenWildcardAllowedOrigin_WhenNonOptionsRequestHandledFromAllowedOrigin_AllowedOriginHeaderSet(): void
    {
        $middleware = new CorsMiddleware(new AllowedOrigins(['*']));
        $response = new Response();

        $middleware->execute($this->requestWithOrigin(Request::METHOD_POST, 'https://example.net'), $response);

        self::assertEquals('https://example.net', $response->headers->get('Access-Control-Allow-Origin'));
        self::assertEquals('true', $response->headers->get('Access-Control-Allow-Credentials'));
    }

    /** @test */
    public function GivenWildcardAllowedOrigin_WhenNonOptionsRequestHandledFromAllowedOrigin_AllowedMethodsHeaderSet(): void
    {
        $middleware = new CorsMiddleware(new AllowedOrigins(['*']));
        $response = new Response();

        $middleware->execute($this->requestWithOrigin(Request::METHOD_POST, 'https://example.net'), $response);

        self::assertNull($response->headers->get('Access-Control-Allow-Methods'));
        self::assertNull($response->headers->get('Access-Control-Max-Age'));
    }

    private function requestWithOrigin(string $method, string $origin): Request
    {
        return Request::create(
            '/',
            $method,
            [],
            [],
            [],
            [
                'HTTP_ORIGIN' => $origin
            ]
        );
    }

    private function requestWithoutOrigin(string $method): Request
    {
        return Request::create(
            '/',
            $method
        );
    }
}
