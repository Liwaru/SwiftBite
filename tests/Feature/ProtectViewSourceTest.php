<?php

namespace Tests\Feature;

use App\Http\Middleware\ProtectViewSource;
use Tests\TestCase;

class ProtectViewSourceTest extends TestCase
{
    public function test_security_shortcuts_are_injected_into_html_responses(): void
    {
        $middleware = app(ProtectViewSource::class);

        $response = $middleware->handle(
            request(),
            fn () => response('<html><head><title>SwiftBite</title></head><body>OK</body></html>')
        );

        $content = $response->getContent();

        $this->assertStringContainsString('id="swiftbite-security-shortcuts"', $content);
        $this->assertStringContainsString('Ctrl', $content);
        $this->assertLessThan(strpos($content, '</head>'), strpos($content, 'swiftbite-security-shortcuts'));
    }

    public function test_security_shortcuts_are_not_injected_twice(): void
    {
        $middleware = app(ProtectViewSource::class);

        $response = $middleware->handle(
            request(),
            fn () => response('<html><head><script id="swiftbite-security-shortcuts"></script></head><body>OK</body></html>')
        );

        $this->assertSame(1, substr_count($response->getContent(), 'swiftbite-security-shortcuts'));
    }
}
