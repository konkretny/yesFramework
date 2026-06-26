<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use yesFramework\Core\Classes\Request;

class RequestTest extends TestCase
{
    public function testGetAndPostFallbacks(): void
    {
        $_GET['test_key'] = 'test_value';
        $_POST['post_key'] = 'post_value';

        // Retrieve single key
        $this->assertEquals('test_value', Request::get('test_key'));
        $this->assertEquals('post_value', Request::post('post_key'));
        $this->assertNull(Request::get('nonexistent'));

        // Retrieve all
        $allGet = Request::get();
        $allPost = Request::post();
        $this->assertEquals('test_value', $allGet['test_key']);
        $this->assertEquals('post_value', $allPost['post_key']);

        unset($_GET['test_key'], $_POST['post_key']);
    }
}
