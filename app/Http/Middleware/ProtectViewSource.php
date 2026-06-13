<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProtectViewSource
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $this->isHtmlResponse($response)) {
            return $response;
        }

        $content = (string) $response->getContent();

        if (str_contains($content, 'swiftbite-security-shortcuts')) {
            return $response;
        }

        $allowInspect = $request->routeIs('baker.*', 'customer.menu', 'waiter.dashboard');

        $script = view('partials.security-shortcuts', [
            'allowInspect' => $allowInspect,
            'allowViewSource' => false,
        ])->render();

        if (str_contains($content, '</head>')) {
            $content = str_replace('</head>', $script.'</head>', $content);
        } elseif (str_contains($content, '</body>')) {
            $content = str_replace('</body>', $script.'</body>', $content);
        } else {
            $content .= $script;
        }

        $response->setContent($content);

        return $response;
    }

    private function isHtmlResponse(Response $response): bool
    {
        $contentType = (string) $response->headers->get('Content-Type');

        return str_contains($contentType, 'text/html') || $contentType === '';
    }
}
