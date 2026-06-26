<?php

declare(strict_types=1);

namespace yesFramework\Core\Classes;

class Http
{
    /**
     * Redirects the user to the specified variable path.
     */
    public static function redirect(string $url, bool $redirect_301 = false): void
    {
        if ($redirect_301) {
            header("HTTP/1.1 301 Moved Permanently");
        }
        header('Location: ' . $url);
        exit;
    }

    /**
     * Get IP address
     */
    public static function getIP(): string
    {
        return $_SERVER['REMOTE_ADDR'] ?? '';
    }

    /**
     * Get server IP address.
     */
    public static function getServIP(): string
    {
        return $_SERVER['HTTP_REFERER'] ?? '';
    }

    /**
     * Gets the security code before the attack CSRF.
     */
    public static function getCSRF(): string
    {
        return $_SESSION['csrf'] ?? '';
    }

    /**
     * Return json result
     */
    public static function jsonResult(string $status, string $message, string $message_type): string
    {
        return json_encode([
            "status" => $status,
            "payload" => [
                'message' => $message,
                'messageType' => $message_type
            ]
        ]) ?: '{}';
    }

    /**
     * Fix
     */
    public static function setCORSHeaders(): void
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: *');
        header('Access-Control-Allow-Headers: Content-Type');
    }

    /**
     * Universal CURL function
     */
    public static function curl(string $url, string $method = "GET", int $timeout = 10, bool $cert_verify = true, array $params = [], array $headers = []): array
    {
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($c, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, $cert_verify);

        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_HEADER, false);

        if (strtoupper($method) === 'POST') {
            curl_setopt($c, CURLOPT_POST, 1);
            curl_setopt($c, CURLOPT_POSTFIELDS, http_build_query($params));
        }

        if (!empty($headers)) {
            curl_setopt($c, CURLOPT_HTTPHEADER, $headers);
        }

        $output = curl_exec($c);
        $http_code = curl_getinfo($c, CURLINFO_HTTP_CODE);
        curl_close($c);

        return [$output ?: 'curl error', ['httpCode' => $http_code]];
    }

    /**
     * Makes a call CURL as GET
     */
    public static function curlGet(string $url, int $time = 10, bool $cert_verify = true): string
    {
        $result = self::curl($url, 'GET', $time, $cert_verify);
        return (string)$result[0];
    }

    public static function curlGetHeader(string $url, int $time = 10, bool $cert_verify = true, array $headers = []): string
    {
        $result = self::curl($url, 'GET', $time, $cert_verify, [], $headers);
        return (string)$result[0];
    }

    public static function curlPost(string $url, int $time = 10, bool $cert_verify = true, array $params = []): string
    {
        $result = self::curl($url, 'POST', $time, $cert_verify, $params);
        return (string)$result[0];
    }

    public static function curlPostHeader(string $url, int $time = 10, bool $cert_verify = true, array $params = [], array $headers = []): string
    {
        $result = self::curl($url, 'POST', $time, $cert_verify, $params, $headers);
        return (string)$result[0];
    }

    public static function curlJsonPost(string $url, int $time = 10, bool $cert_verify = true, string $params = ""): string
    {
        $headers = ['Content-Type: application/json'];
        // Note: For JSON post we need to pass the raw string
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_TIMEOUT, $time);
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, $cert_verify);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_HEADER, false);
        curl_setopt($c, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($c, CURLOPT_POST, 1);
        curl_setopt($c, CURLOPT_POSTFIELDS, 'json=' . $params);
        $output = curl_exec($c);
        if ($output === false) {
            $output = 'curl error';
        }
        curl_close($c);
        return $output;
    }
}
