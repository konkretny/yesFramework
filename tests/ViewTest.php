<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use yesFramework\Core\Classes\View;

class ViewTest extends TestCase
{
    public function testInputGeneratesEscapedHtml(): void
    {
        $input = View::input('text', 'username', [
            'id' => 'user-id',
            'value' => '"><script>alert(1)</script>'
        ]);

        $this->assertStringContainsString('type="text"', $input);
        $this->assertStringContainsString('name="username"', $input);
        $this->assertStringContainsString('id="user-id"', $input);
        $this->assertStringContainsString('value="&quot;&gt;&lt;script&gt;alert(1)&lt;/script&gt;"', $input);
        $this->assertStringNotContainsString('"><script>', $input);
    }

    public function testOptionGeneratesEscapedHtml(): void
    {
        $option = View::option('"><script>alert(1)</script>', 'Escaped Option');

        $this->assertStringContainsString('value="&quot;&gt;&lt;script&gt;alert(1)&lt;/script&gt;"', $option);
        $this->assertStringContainsString('Escaped Option', $option);
        $this->assertStringNotContainsString('"><script>', $option);
    }
}
