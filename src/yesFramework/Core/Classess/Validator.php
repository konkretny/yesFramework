<?php

namespace yesFramework\Core\Classess;

interface ValidatorInterface
{
    public static function check_email(string $email): void;
    public static function check_ip(string $ip): void;
    public static function check_integer(int $data, bool $param): void;
    public static function rule_no_empty(array $array = [], array $param = []): void;
    public static function check_integer_in_array(bool $param, array $array = []): void;
}

/**
 * Validation class
 */
class Validator implements ValidatorInterface
{

    /**
     * Validates e-mail
     * @param string $email
     */
    public static function check_email(string $email): void
    {
        if (check_email($email) !== true) {
            echo 'Error validate e-mial';
            exit;
        }
    }

    /**
     * Validates IP
     * @param string $ip
     */
    public static function check_ip(string $ip): void
    {
        if (check_ip($ip) !== true) {
            echo 'Error validate ip';
            exit;
        }
    }

    /**
     * Validates int
     * @param mixed $data
     * @param int $param
     */
    public static function check_integer(int $data, bool $param): void
    {
        if (check_integer($data, $param) !== true) {
            echo 'Error validate integer';
            exit;
        }
    }

    /**
     * Check empty var
     * @param mixed[] $array
     * @param mixed[] $param
     */
    public static function rule_no_empty(array $array = [], array $param = []): void
    {
        if (rule_no_empty($array, $param) !== true) {
            echo 'Error validate empty value';
            exit;
        }
    }

    /**
     * Check integer in array
     * @param int $param
     * @param mixed[] $array
     */
    public static function check_integer_in_array(bool $param, array $array = []): void
    {
        if (check_integer_in_array($param, $array) !== true) {
            echo 'Error validate integer in array';
            exit;
        }
    }
}
