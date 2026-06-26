<?php

declare(strict_types=1);

namespace yesFramework\Core\Exceptions;

/**
 * Exception thrown for database-related errors
 */
class DatabaseException extends FrameworkException
{
    public function __construct(
        string $message = 'Database error',
        int $code = 500,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
