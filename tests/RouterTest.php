<?php

namespace App\Tests;

use App\Container\Container;
use App\Http\Request;
use App\Http\Response;
use App\Routing\Router;
use PHPUnit\Framework\TestCase;

// Mock Controller for testing
class MockController
{
    public function index()
    {
        return new Response('Hello World');
    }

    public function show($id)
    {
        return new Response("Article {$id}");
    }
}

class RouterTest extends TestCase
{
    private $container;
    private $router;

    protected function setUp(): void
    {
        $this->container = new Container();
        $this->container->bind(MockController::class);
        $this->router = new Router($this->container);
    }

    public function testSimpleGetRoute()
    {
        $this->router->addRoute('GET', '/', [MockController::class, 'index']);

        $request = $this->createMock(Request::class);
        $request->method('getMethod')->willReturn('GET');
        $request->method('getUri')->willReturn('/');

        $response = $this->router->handle($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Hello World', $response->getContent());
    }

    public function testRouteWithPlaceholder()
    {
        $this->router->addRoute('GET', '/article/{id}', [MockController::class, 'show']);

        $request = $this->createMock(Request::class);
        $request->method('getMethod')->willReturn('GET');
        $request->method('getUri')->willReturn('/article/123');

        $response = $this->router->handle($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Article 123', $response->getContent());
    }

    public function testNotFoundRoute()
    {
        $this->router->addRoute('GET', '/', [MockController::class, 'index']);

        $request = $this->createMock(Request::class);
        $request->method('getMethod')->willReturn('GET');
        $request->method('getUri')->willReturn('/not-found');

        $response = $this->router->handle($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testRouteWithClosure()
    {
        $this->router->addRoute('GET', '/closure/{name}', function($name) {
            return new Response("Hello {$name}");
        });

        $request = $this->createMock(Request::class);
        $request->method('getMethod')->willReturn('GET');
        $request->method('getUri')->willReturn('/closure/Gemini');

        $response = $this->router->handle($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Hello Gemini', $response->getContent());
    }
}
