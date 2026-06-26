<?php

declare(strict_types=1);

namespace yesFramework\Core\Classes;

/**
 * Validation class
 */
class Validator
{

    public static function isEmail(string $email): bool
    {
        return (bool)filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public static function isIp(string $ip): bool
    {
        return (bool)filter_var($ip, FILTER_VALIDATE_IP);
    }

    public static function isInteger($data, bool $allowNegative = false): bool
    {
        if ($allowNegative) {
            return preg_match('/^-?[0-9]+$/', (string)$data) === 1;
        }
        return preg_match('/^[0-9]+$/', (string)$data) === 1;
    }

    public static function isIntegerInArray(array $array, bool $allowNegative = false): bool
    {
        foreach ($array as $value) {
            if (!self::isInteger($value, $allowNegative)) {
                return false;
            }
        }
        return true;
    }

    public static function noEmpty(array $array, array $keysToCheck = []): bool
    {
        if (empty($keysToCheck) || (isset($keysToCheck[0]) && $keysToCheck[0] === 'ALL')) {
            $keysToCheck = array_keys($array);
        }

        foreach ($keysToCheck as $key) {
            if (!array_key_exists($key, $array)) {
                return false;
            }
        }

        foreach ($array as $key => $value) {
            if (in_array($key, $keysToCheck) && empty(trim((string)$value))) {
                return false;
            }
        }
        return true;
    }

    public static function isTrueEmpty($data): bool
    {
        if ($data === 0 || $data === '0') {
            return false;
        }
        return empty(trim((string)$data));
    }
}
