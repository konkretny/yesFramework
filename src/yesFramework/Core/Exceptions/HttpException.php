<?php

declare(strict_types=1);

namespace yesFramework\Core\Exceptions;

/**
 * Generic HTTP exception with a status code
 */
class HttpException extends FrameworkException
{
    private int $statusCode;

    public function __construct(
        string $message = 'HTTP Error',
        int $statusCode = 500,
        ?\Throwable $previous = null
    ) {
        $this->statusCode = $statusCode;
        parent::__construct($message, $statusCode, $previous);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
