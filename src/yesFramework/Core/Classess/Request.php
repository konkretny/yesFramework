<?php

namespace yesFramework\Core\Classess;

interface RequestInterface
{
    public static function get(string $value);
    public static function post(string $value);
    public static function session(string $value);
    public static function server(string $value);
    public static function cookies(string $value);
}

/**
 * Request handler class
 */
class Request implements RequestInterface
{

    /**
     * GET Request
     * @global mixed[] $_GET
     * @param mixed[] $value
     * @return mixed[]
     */
    public static function get(string $value)
    {
        global $_GET;
        if (!empty(trim($value))) {
            if (isset($_GET[$value])) {
                $result = $_GET[$value];
            } else {
                $result = NULL;
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
     * @global mixed[] $_POST
     * @param mixed[] $value
     * @return mixed[]
     */
    public static function post(string $value)
    {
        global $_POST;
        if (!empty(trim($value))) {
            if (isset($_POST[$value])) {
                $result = $_POST[$value];
            } else {
                $result = NULL;
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
     * @global mixed[] $_SESSION
     * @param mixed[] $value
     * @return mixed[]
     */
    public static function session(string $value)
    {
        global $_SESSION;
        if (!empty(trim($value))) {
            if (isset($_SESSION[$value])) {
                $result = $_SESSION[$value];
            } else {
                $result = NULL;
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
     * @global mixed[] $_SERVER
     * @param mixed[] $value
     * @return mixed[]
     */
    public static function server(string $value)
    {
        global $_SERVER;
        if (!empty(trim($value))) {
            if (isset($_SERVER[$value])) {
                $result = $_SERVER[$value];
            } else {
                $result = NULL;
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
     * @global mixed[] $_COOKIE
     * @param mixed[] $value
     * @return mixed[]
     */
    public static function cookies(string $value)
    {
        global $_COOKIE;
        if (!empty(trim($value))) {
            if (isset($_COOKIE[$value])) {
                $result = $_COOKIE[$value];
            } else {
                $result = NULL;
            }
        } elseif (isset($_COOKIE)) {
            $result = $_COOKIE;
        } else {
            $result = [];
        }
        return $result;
    }
}
