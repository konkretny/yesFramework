<?php

declare(strict_types=1);

namespace yesFramework\Core\Exceptions;

/**
 * Base exception for the yesFramework
 */
class FrameworkException extends \RuntimeException
{
    public function __construct(
        string $message = '',
        int $code = 500,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
