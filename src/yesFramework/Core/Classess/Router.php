<?php

declare(strict_types=1);

namespace yesFramework\Core\Classess;

/**
 * Simple Router class for resolving request URIs
 */
class Router
{
    private array $routes = [];
    private ?Db $db;

    public function __construct(?Db $db = null)
    {
        $this->db = $db;
    }

    public function get(string $path, callable|array $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, callable|array $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    private function addRoute(string $method, string $path, callable|array $handler): void
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function resolve(string $requestMethod, string $requestUri): void
    {
        // Strip query string
        if (false !== $pos = strpos($requestUri, '?')) {
            $requestUri = substr($requestUri, 0, $pos);
        }

        // Basic normalization
        if ($requestUri !== '/') {
            $requestUri = rtrim($requestUri, '/');
        }

        $requestUri = $requestUri ?: '/';

        foreach ($this->routes as $route) {
            if ($route['method'] === $requestMethod && $route['path'] === $requestUri) {
                $handler = $route['handler'];

                if (is_array($handler)) {
                    // Controller instantiation
                    $controllerClass = $handler[0];
                    $methodName = $handler[1];

                    // Basic DI for DB
                    $controllerInstance = new $controllerClass($this->db);
                    call_user_func([$controllerInstance, $methodName]);
                } else {
                    call_user_func($handler);
                }
                return;
            }
        }

        // 404 Not Found
        http_response_code(404);
        echo "404 Not Found";
    }
}
