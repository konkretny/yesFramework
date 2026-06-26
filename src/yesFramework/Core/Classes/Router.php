<?php

declare(strict_types=1);

namespace yesFramework\Core\Classes;

use yesFramework\Core\Exceptions\NotFoundException;

/**
 * Router with dynamic route parameters and middleware support
 *
 * Supports:
 * - Static routes: /about, /contact
 * - Dynamic parameters: /user/{id}, /post/{slug}/comments
 * - HTTP methods: GET, POST, PUT, DELETE, PATCH
 * - Global and per-route middleware
 */
class Router
{
    private array $routes = [];

    /** @var string[] Global middleware class names */
    private array $globalMiddleware = [];

    private ?Db $db;

    public function __construct(?Db $db = null)
    {
        $this->db = $db;
    }

    public function get(string $path, callable|array $handler): self
    {
        return $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, callable|array $handler): self
    {
        return $this->addRoute('POST', $path, $handler);
    }

    public function put(string $path, callable|array $handler): self
    {
        return $this->addRoute('PUT', $path, $handler);
    }

    public function delete(string $path, callable|array $handler): self
    {
        return $this->addRoute('DELETE', $path, $handler);
    }

    public function patch(string $path, callable|array $handler): self
    {
        return $this->addRoute('PATCH', $path, $handler);
    }

    /**
     * Add global middleware (applied to all routes)
     *
     * @param string $middlewareClass Fully qualified class name implementing MiddlewareInterface
     */
    public function addMiddleware(string $middlewareClass): self
    {
        $this->globalMiddleware[] = $middlewareClass;
        return $this;
    }

    /**
     * Add per-route middleware to the last registered route
     *
     * @param string $middlewareClass Fully qualified class name implementing MiddlewareInterface
     */
    public function middleware(string $middlewareClass): self
    {
        $lastIndex = array_key_last($this->routes);
        if ($lastIndex !== null) {
            $this->routes[$lastIndex]['middleware'][] = $middlewareClass;
        }
        return $this;
    }

    private function addRoute(string $method, string $path, callable|array $handler): self
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler,
            'middleware' => [],
            'pattern' => $this->compilePattern($path),
            'paramNames' => $this->extractParamNames($path),
        ];
        return $this;
    }

    /**
     * Convert route path like /user/{id}/post/{slug} to a regex pattern
     */
    private function compilePattern(string $path): string
    {
        // Escape forward slashes and replace {param} with named capture groups
        $pattern = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $path);
        return '#^' . $pattern . '$#';
    }

    /**
     * Extract parameter names from route path
     *
     * @return string[]
     */
    private function extractParamNames(string $path): array
    {
        preg_match_all('/\{(\w+)\}/', $path, $matches);
        return $matches[1];
    }

    /**
     * Resolve the current request against registered routes
     *
     * @throws NotFoundException When no matching route is found
     */
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
            if ($route['method'] !== $requestMethod) {
                continue;
            }

            if (preg_match($route['pattern'], $requestUri, $matches)) {
                // Extract named parameters
                $params = [];
                foreach ($route['paramNames'] as $name) {
                    $params[$name] = $matches[$name] ?? null;
                }

                // Build middleware pipeline (global + route-specific)
                $allMiddleware = array_merge($this->globalMiddleware, $route['middleware']);

                // Execute handler through middleware pipeline
                $handler = fn() => $this->executeHandler($route['handler'], $params);
                $pipeline = $this->buildPipeline($allMiddleware, $handler);
                $pipeline();

                return;
            }
        }

        // 404 Not Found
        throw new NotFoundException("Route not found: {$requestMethod} {$requestUri}");
    }

    /**
     * Execute a route handler (callable or controller method)
     *
     * @param callable|array $handler
     * @param array<string, string|null> $params Route parameters
     */
    private function executeHandler(callable|array $handler, array $params): void
    {
        if (is_array($handler)) {
            // Controller instantiation with basic DI
            $controllerClass = $handler[0];
            $methodName = $handler[1];

            $controllerInstance = new $controllerClass($this->db);
            call_user_func_array([$controllerInstance, $methodName], $params);
        } else {
            call_user_func_array($handler, $params);
        }
    }

    /**
     * Build a middleware pipeline using the chain of responsibility pattern
     *
     * @param string[] $middlewareClasses
     * @param callable $handler The final handler to call
     * @return callable
     */
    private function buildPipeline(array $middlewareClasses, callable $handler): callable
    {
        $pipeline = $handler;

        // Wrap handlers from inside out
        foreach (array_reverse($middlewareClasses) as $middlewareClass) {
            $next = $pipeline;
            $pipeline = function () use ($middlewareClass, $next) {
                /** @var MiddlewareInterface $middleware */
                $middleware = new $middlewareClass();
                $middleware->handle($next);
            };
        }

        return $pipeline;
    }
}
