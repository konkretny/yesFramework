<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use yesFramework\Core\Classess\Router;
use yesFramework\Core\Classess\Db;

class RouterTest extends TestCase
{
    public function testRouterRegistersGetRoutes()
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

    public function testRouterRegistersPostRoutes()
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
}
