<?php

declare(strict_types=1);

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Tests\Unit\Helpers;

use PHPUnit\Framework\TestCase;
use WPframework\Support\Str;

/**
 * @group WPframework
 *
 * @covers ::Str::sanitize
 *
 * @internal
 */
class AppSanitizerTest extends TestCase
{
    public function test_wp_sanitize_with_script_tags(): void
    {
        $input = "<script>alert('hello');</script>";
        $expected = "alert(&#039hello&#039)";
        $sanitized = Str::sanitize($input);
        $this->assertEquals($expected, $sanitized);
    }

    public function test_wp_sanitize_with_html_entities(): void
    {
        $input = "&lt;script&gt;alert(&#39;hello&#39;);&lt;/script&gt;";
        $expected = "&ampltscript&ampgtalert(&amp#39hello&amp#39)&amplt/script&ampgt";
        $sanitized = Str::sanitize($input);
        $this->assertEquals($expected, $sanitized);
    }

    public function test_wp_sanitize_with_whitespace(): void
    {
        $input = "   hello   ";
        $expected = "hello";
        $sanitized = Str::sanitize($input);
        $this->assertEquals($expected, $sanitized);
    }

    public function test_wp_sanitize_empty_input(): void
    {
        $input = "";
        $expected = "";
        $sanitized = Str::sanitize($input);
        $this->assertEquals($expected, $sanitized);
    }

    public function test_wp_sanitize_with_css(): void
    {
        $input = "color: red; background-image: url('malicious.png');";
        $expected = "color: red background-image: url(&#039malicious.png&#039)";
        $sanitized = Str::sanitize($input);
        $this->assertEquals($expected, $sanitized);
    }

    public function test_wp_sanitize_with_html(): void
    {
        $input = "<p><a href='http://example.com'>Link</a></p>";
        $expected = "Link";
        $sanitized = Str::sanitize($input);
        $this->assertEquals($expected, $sanitized);
    }
}
