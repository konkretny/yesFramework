<?php

declare(strict_types=1);

namespace yesFramework\Core\Classes;

interface RequestInterface
{
    public static function get(string $value): mixed;
    public static function post(string $value): mixed;
    public static function session(string $value): mixed;
    public static function server(string $value): mixed;
    public static function cookies(string $value): mixed;
}

/**
 * Request handler class
 */
class Request implements RequestInterface
{

    /**
     * GET Request
     * @param string $value
     * @return mixed
     */
    public static function get(string $value): mixed
    {
        if (!empty(trim($value))) {
            if (isset($_GET[$value])) {
                $result = $_GET[$value];
            } else {
                $result = null;
            }
        } elseif (isset($_GET)) {
            $result = $_GET;
        } else {
            $result = [];
        }
        return $result;
    }

    /**
     * POST Request
     * @param string $value
     * @return mixed
     */
    public static function post(string $value): mixed
    {
        if (!empty(trim($value))) {
            if (isset($_POST[$value])) {
                $result = $_POST[$value];
            } else {
                $result = null;
            }
        } elseif (isset($_POST)) {
            $result = $_POST;
        } else {
            $result = [];
        }
        return $result;
    }

    /**
     * SESSION Request
     * @param string $value
     * @return mixed
     */
    public static function session(string $value): mixed
    {
        if (!empty(trim($value))) {
            if (isset($_SESSION[$value])) {
                $result = $_SESSION[$value];
            } else {
                $result = null;
            }
        } elseif (isset($_SESSION)) {
            $result = $_SESSION;
        } else {
            $result = [];
        }
        return $result;
    }

    /**
     * SERVER Request
     * @param string $value
     * @return mixed
     */
    public static function server(string $value): mixed
    {
        if (!empty(trim($value))) {
            if (isset($_SERVER[$value])) {
                $result = $_SERVER[$value];
            } else {
                $result = null;
            }
        } elseif (isset($_SERVER)) {
            $result = $_SERVER;
        } else {
            $result = [];
        }
        return $result;
    }

    /**
     * COOKIE Request
     * @param string $value
     * @return mixed
     */
    public static function cookies(string $value): mixed
    {
        if (!empty(trim($value))) {
            if (isset($_COOKIE[$value])) {
                $result = $_COOKIE[$value];
            } else {
                $result = null;
            }
        } elseif (isset($_COOKIE)) {
            $result = $_COOKIE;
        } else {
            $result = [];
        }
        return $result;
    }
}
