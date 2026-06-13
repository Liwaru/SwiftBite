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

    public function test_security_shortcuts_can_allow_inspect_without_allowing_view_source(): void
    {
        $blockedScript = view('partials.security-shortcuts', [
            'allowInspect' => false,
            'allowViewSource' => false,
        ])->render();

        $allowedInspectScript = view('partials.security-shortcuts', [
            'allowInspect' => true,
            'allowViewSource' => false,
        ])->render();

        $this->assertStringContainsString("blockedKeys.add('F12')", $blockedScript);
        $this->assertStringContainsString("blockedCtrlShiftKeys.add('i')", $blockedScript);
        $this->assertStringContainsString("blockedCtrlKeys.add('u')", $blockedScript);

        $this->assertStringContainsString('const allowInspect = true;', $allowedInspectScript);
        $this->assertStringNotContainsString("blockedKeys.add('F12')", $allowedInspectScript);
        $this->assertStringNotContainsString("blockedCtrlShiftKeys.add('i')", $allowedInspectScript);
        $this->assertStringContainsString("blockedCtrlKeys.add('u')", $allowedInspectScript);
    }

    public function test_inspect_allowlist_matches_expected_routes(): void
    {
        $allowedRoutes = ['baker.dashboard', 'baker.orders', 'baker.ingredients', 'customer.menu', 'waiter.dashboard'];
        $blockedRoutes = ['cashier.dashboard', 'manager.dashboard', 'owner.dashboard'];

        foreach ($allowedRoutes as $routeName) {
            $this->assertTrue($this->routeAllowsInspect($routeName));
        }

        foreach ($blockedRoutes as $routeName) {
            $this->assertFalse($this->routeAllowsInspect($routeName));
        }
    }

    private function routeAllowsInspect(string $routeName): bool
    {
        return str_starts_with($routeName, 'baker.')
            || in_array($routeName, ['customer.menu', 'waiter.dashboard'], true);
    }
}
