<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use yesFramework\Core\Classes\Router;
use yesFramework\Core\Classes\Db;
use yesFramework\Core\Exceptions\NotFoundException;

class RouterTest extends TestCase
{
    public function testRouterRegistersGetRoutes(): void
    {
        $router = new Router(null);
        $router->get('/test', function() { echo "Test"; });

        // Using reflection to inspect private routes array
        $reflection = new \ReflectionClass($router);
        $property = $reflection->getProperty('routes');
        $property->setAccessible(true);
        $routes = $property->getValue($router);

        $this->assertCount(1, $routes);
        $this->assertEquals('GET', $routes[0]['method']);
        $this->assertEquals('/test', $routes[0]['path']);
    }

    public function testRouterRegistersPostRoutes(): void
    {
        $router = new Router(null);
        $router->post('/submit', function() { echo "Submit"; });

        $reflection = new \ReflectionClass($router);
        $property = $reflection->getProperty('routes');
        $property->setAccessible(true);
        $routes = $property->getValue($router);

        $this->assertCount(1, $routes);
        $this->assertEquals('POST', $routes[0]['method']);
        $this->assertEquals('/submit', $routes[0]['path']);
    }

    public function testRouterRegistersPutAndDeleteRoutes(): void
    {
        $router = new Router(null);
        $router->put('/update', function() { echo "Update"; });
        $router->delete('/remove', function() { echo "Remove"; });

        $reflection = new \ReflectionClass($router);
        $property = $reflection->getProperty('routes');
        $property->setAccessible(true);
        $routes = $property->getValue($router);

        $this->assertCount(2, $routes);
        $this->assertEquals('PUT', $routes[0]['method']);
        $this->assertEquals('DELETE', $routes[1]['method']);
    }

    public function testRouterResolvesStaticRoute(): void
    {
        $router = new Router(null);
        $called = false;
        $router->get('/hello', function() use (&$called) { $called = true; });

        ob_start();
        $router->resolve('GET', '/hello');
        ob_end_clean();

        $this->assertTrue($called);
    }

    public function testRouterResolvesRouteWithParameters(): void
    {
        $router = new Router(null);
        $capturedId = null;
        $router->get('/user/{id}', function(string $id) use (&$capturedId) {
            $capturedId = $id;
        });

        ob_start();
        $router->resolve('GET', '/user/42');
        ob_end_clean();

        $this->assertEquals('42', $capturedId);
    }

    public function testRouterResolvesRouteWithMultipleParameters(): void
    {
        $router = new Router(null);
        $capturedParams = [];
        $router->get('/post/{slug}/comment/{commentId}', function(string $slug, string $commentId) use (&$capturedParams) {
            $capturedParams = ['slug' => $slug, 'commentId' => $commentId];
        });

        ob_start();
        $router->resolve('GET', '/post/hello-world/comment/99');
        ob_end_clean();

        $this->assertEquals('hello-world', $capturedParams['slug']);
        $this->assertEquals('99', $capturedParams['commentId']);
    }

    public function testRouterThrowsNotFoundForUnknownRoute(): void
    {
        $this->expectException(NotFoundException::class);

        $router = new Router(null);
        $router->resolve('GET', '/nonexistent');
    }

    public function testRouterStripsQueryString(): void
    {
        $router = new Router(null);
        $called = false;
        $router->get('/search', function() use (&$called) { $called = true; });

        ob_start();
        $router->resolve('GET', '/search?q=test&page=1');
        ob_end_clean();

        $this->assertTrue($called);
    }
}
