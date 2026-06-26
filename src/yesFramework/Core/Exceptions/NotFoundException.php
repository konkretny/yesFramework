<?php

declare(strict_types=1);

namespace yesFramework\Core\Exceptions;

/**
 * Exception thrown when a requested resource is not found (404)
 */
class NotFoundException extends HttpException
{
    public function __construct(
        string $message = 'Not Found',
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, 404, $previous);
    }
}
