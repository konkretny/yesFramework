<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use yesFramework\Core\Classes\Validator;

class ValidatorTest extends TestCase
{
    public function testIsEmail(): void
    {
        $this->assertTrue(Validator::isEmail('test@example.com'));
        $this->assertFalse(Validator::isEmail('invalid-email'));
    }

    public function testIsIp(): void
    {
        $this->assertTrue(Validator::isIp('127.0.0.1'));
        $this->assertFalse(Validator::isIp('256.256.256.256'));
    }

    public function testIsInteger(): void
    {
        $this->assertTrue(Validator::isInteger(42));
        $this->assertTrue(Validator::isInteger('42'));

        $this->assertFalse(Validator::isInteger(-42));
        $this->assertTrue(Validator::isInteger(-42, true));

        $this->assertFalse(Validator::isInteger('abc'));
        $this->assertFalse(Validator::isInteger([]));
        $this->assertFalse(Validator::isInteger(new \stdClass()));
    }

    public function testIsIntegerInArray(): void
    {
        $this->assertTrue(Validator::isIntegerInArray([1, 2, '3']));
        $this->assertFalse(Validator::isIntegerInArray([1, 'abc', 3]));
        $this->assertFalse(Validator::isIntegerInArray([1, -2, 3], false));
        $this->assertTrue(Validator::isIntegerInArray([1, -2, 3], true));
        $this->assertFalse(Validator::isIntegerInArray([1, [], 3]));
    }

    public function testNoEmpty(): void
    {
        $arr = ['a' => 'foo', 'b' => '', 'c' => []];

        $this->assertFalse(Validator::noEmpty($arr, ['a', 'b']));
        $this->assertTrue(Validator::noEmpty($arr, ['a']));
        $this->assertFalse(Validator::noEmpty($arr, ['d'])); // missing key
        $this->assertFalse(Validator::noEmpty($arr, ['c'])); // empty array key
    }

    public function testIsTrueEmpty(): void
    {
        $this->assertTrue(Validator::isTrueEmpty(''));
        $this->assertTrue(Validator::isTrueEmpty('   '));
        $this->assertTrue(Validator::isTrueEmpty([]));
        $this->assertTrue(Validator::isTrueEmpty(new \stdClass()));

        $this->assertFalse(Validator::isTrueEmpty(0));
        $this->assertFalse(Validator::isTrueEmpty('0'));
        $this->assertFalse(Validator::isTrueEmpty('text'));
        $this->assertFalse(Validator::isTrueEmpty(['foo']));
    }
}
