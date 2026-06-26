<?php

declare(strict_types=1);

namespace yesFramework\Core\Classes;

/**
 * Middleware interface for the request pipeline
 *
 * Middleware can inspect/modify the request before it reaches the controller,
 * or halt the pipeline entirely (e.g., for authentication).
 *
 * Usage:
 *   class AuthMiddleware implements MiddlewareInterface {
 *       public function handle(callable $next): void {
 *           if (!isset($_SESSION['user'])) {
 *               http_response_code(401);
 *               echo 'Unauthorized';
 *               return;
 *           }
 *           $next();
 *       }
 *   }
 */
interface MiddlewareInterface
{
    /**
     * Handle the request
     *
     * @param callable $next Call this to pass control to the next middleware or the route handler
     */
    public function handle(callable $next): void;
}
