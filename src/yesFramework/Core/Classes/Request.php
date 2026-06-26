<?php

declare(strict_types=1);

namespace yesFramework\Core\Classes;

interface RequestInterface
{
    public static function get(?string $value = null): mixed;
    public static function post(?string $value = null): mixed;
    public static function session(?string $value = null): mixed;
    public static function server(?string $value = null): mixed;
    public static function cookies(?string $value = null): mixed;
}

/**
 * Request handler class
 */
class Request implements RequestInterface
{

    /**
     * GET Request
     */
    public static function get(?string $value = null): mixed
    {
        if ($value !== null && trim($value) !== '') {
            return $_GET[$value] ?? null;
        }
        return $_GET ?? [];
    }

    /**
     * POST Request
     */
    public static function post(?string $value = null): mixed
    {
        if ($value !== null && trim($value) !== '') {
            return $_POST[$value] ?? null;
        }
        return $_POST ?? [];
    }

    /**
     * SESSION Request
     */
    public static function session(?string $value = null): mixed
    {
        if ($value !== null && trim($value) !== '') {
            return $_SESSION[$value] ?? null;
        }
        return $_SESSION ?? [];
    }

    /**
     * SERVER Request
     */
    public static function server(?string $value = null): mixed
    {
        if ($value !== null && trim($value) !== '') {
            return $_SERVER[$value] ?? null;
        }
        return $_SERVER ?? [];
    }

    /**
     * COOKIE Request
     */
    public static function cookies(?string $value = null): mixed
    {
        if ($value !== null && trim($value) !== '') {
            return $_COOKIE[$value] ?? null;
        }
        return $_COOKIE ?? [];
    }
}
