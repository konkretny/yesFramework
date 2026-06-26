<?php

declare(strict_types=1);

namespace yesFramework\Core\Classes;

class Str
{
    /**
     * Cleans array with special characters. Returns purified variable.
     */
    public static function secureInput(string $data): string
    {
        $data = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $data);
        $data = strip_tags($data ?? '');
        $data = htmlspecialchars($data ?? '');
        return $data;
    }

    /**
     * Return new converted array
     */
    public static function secureArray(array $array): array
    {
        $newarray = [];
        foreach ($array as $key => $value) {
            $newarray[$key] = self::secureInput((string)$value);
        }
        return $newarray;
    }

    /**
     * Link friendly base64 encode
     */
    public static function urlBase64Encode(string $string): string
    {
        return str_replace(["+", "/", "-", "="], ["__P__", "__S__", "__M__", "__E__"], base64_encode($string));
    }

    /**
     * Link friendly base64 decode
     */
    public static function urlBase64Decode(string $string): string
    {
        return base64_decode(str_replace(["__P__", "__S__", "__M__", "__E__"], ["+", "/", "-", "="], $string));
    }

    /**
     * Check JSON file
     */
    public static function isJson(string $json): bool
    {
        json_decode($json);
        return json_last_error() === JSON_ERROR_NONE;
    }
}
