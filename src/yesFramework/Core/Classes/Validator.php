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

    public static function isInteger(mixed $data, bool $allowNegative = false): bool
    {
        if (!is_scalar($data)) {
            return false;
        }
        $dataStr = (string)$data;
        if ($allowNegative) {
            return preg_match('/^-?[0-9]+$/', $dataStr) === 1;
        }
        return preg_match('/^[0-9]+$/', $dataStr) === 1;
    }

    public static function isIntegerInArray(array $array, bool $allowNegative = false): bool
    {
        return array_all($array, fn(mixed $value): bool => self::isInteger($value, $allowNegative));
    }

    public static function noEmpty(array $array, array $keysToCheck = []): bool
    {
        if (empty($keysToCheck) || (isset($keysToCheck[0]) && $keysToCheck[0] === 'ALL')) {
            $keysToCheck = array_keys($array);
        }

        foreach ($keysToCheck as $key) {
            if (!array_key_exists($key, $array) || self::isTrueEmpty($array[$key])) {
                return false;
            }
        }
        return true;
    }

    public static function isTrueEmpty(mixed $data): bool
    {
        if ($data === 0 || $data === '0') {
            return false;
        }
        if (is_array($data)) {
            return empty($data);
        }
        if (!is_scalar($data)) {
            return true;
        }
        return trim((string)$data) === '';
    }
}
