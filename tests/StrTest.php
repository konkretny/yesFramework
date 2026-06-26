<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use yesFramework\Core\Classes\Str;

class StrTest extends TestCase
{
    public function testIsJson(): void
    {
        $this->assertTrue(Str::isJson('{"a": 1, "b": "test"}'));
        $this->assertFalse(Str::isJson('{"a": 1, "b": "test"'));
        $this->assertFalse(Str::isJson('not-json'));
    }

    public function testSecureArrayRecursionAndSafety(): void
    {
        $input = [
            'name' => 'John <script>alert(1)</script> Doe',
            'nested' => [
                'val' => '<b>Bold Text</b>',
                'arr' => [1, 2, 3]
            ],
            'number' => 42
        ];

        $secured = Str::secureArray($input);

        $this->assertStringNotContainsString('<script>', $secured['name']);
        $this->assertStringNotContainsString('script', $secured['name']);
        $this->assertEquals('John Doe', preg_replace('/\s+/', ' ', $secured['name']));
        $this->assertEquals('Bold Text', $secured['nested']['val']);
        $this->assertEquals([1, 2, 3], $secured['nested']['arr']);
        $this->assertEquals('42', $secured['number']);
    }
}
