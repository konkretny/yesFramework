<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use yesFramework\Core\Classes\Http;

class HttpTest extends TestCase
{
    public function testGetIpAndRefererFallback(): void
    {
        $_SERVER['REMOTE_ADDR'] = '192.168.1.1';
        $_SERVER['SERVER_ADDR'] = '127.0.0.1';
        $_SERVER['HTTP_REFERER'] = 'https://google.com';

        $this->assertEquals('192.168.1.1', Http::getIP());
        $this->assertEquals('127.0.0.1', Http::getServIP());
        $this->assertEquals('https://google.com', Http::getReferer());

        unset($_SERVER['REMOTE_ADDR'], $_SERVER['SERVER_ADDR'], $_SERVER['HTTP_REFERER']);
    }

    public function testJsonResult(): void
    {
        $json = Http::jsonResult('success', 'Operation complete', 'success');
        $data = json_decode($json, true);

        $this->assertEquals('success', $data['status']);
        $this->assertEquals('Operation complete', $data['payload']['message']);
        $this->assertEquals('success', $data['payload']['messageType']);
    }
}
