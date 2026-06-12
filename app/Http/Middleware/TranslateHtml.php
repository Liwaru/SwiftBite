<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TranslateHtml
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (app()->getLocale() !== 'en' || ! $this->isHtmlResponse($response)) {
            return $response;
        }

        $uiPhrases = trans('ui.phrases');
        $phrases = array_merge(
            $this->pairedTranslations('manager'),
            is_array($uiPhrases) ? $uiPhrases : []
        );

        if (! is_array($phrases) || $phrases === []) {
            return $response;
        }

        uksort($phrases, fn ($a, $b) => strlen($b) <=> strlen($a));

        $response->setContent($this->injectClientTranslator(
            $this->translateHtml((string) $response->getContent(), $phrases),
            $phrases
        ));

        return $response;
    }

    private function isHtmlResponse(Response $response): bool
    {
        $contentType = (string) $response->headers->get('Content-Type');

        return str_contains($contentType, 'text/html') || $contentType === '';
    }

    private function translateHtml(string $html, array $phrases): string
    {
        $parts = preg_split('/(<script\b[^>]*>.*?<\/script>|<style\b[^>]*>.*?<\/style>|<[^>]+>)/is', $html, -1, PREG_SPLIT_DELIM_CAPTURE);

        if ($parts === false) {
            return $html;
        }

        foreach ($parts as $index => $part) {
            if ($part === '') {
                continue;
            }

            if (str_starts_with($part, '<')) {
                if (preg_match('/^<(script|style)\b/i', $part)) {
                    continue;
                }

                $parts[$index] = $this->translateAttributes($part, $phrases);
                continue;
            }

            $parts[$index] = strtr($part, $phrases);
        }

        return implode('', $parts);
    }

    private function translateAttributes(string $tag, array $phrases): string
    {
        $tag = preg_replace_callback(
            '/\b(title|placeholder|aria-label|alt|data-label)=([\'"])(.*?)\2/isu',
            fn ($matches) => $matches[1].'='.$matches[2].strtr($matches[3], $phrases).$matches[2],
            $tag
        ) ?? $tag;

        if (
            preg_match('/^<input\b/i', $tag)
            && preg_match('/\btype=([\'"])(button|submit|reset)\1/i', $tag)
        ) {
            $tag = preg_replace_callback(
                '/\bvalue=([\'"])(.*?)\1/isu',
                fn ($matches) => 'value='.$matches[1].strtr($matches[2], $phrases).$matches[1],
                $tag
            ) ?? $tag;
        }

        return $tag;
    }

    private function injectClientTranslator(string $html, array $phrases): string
    {
        $json = json_encode($phrases, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);

        if ($json === false) {
            return $html;
        }

        $script = <<<HTML
<script>
(function () {
    const phrases = {$json};
    const keys = Object.keys(phrases).sort((a, b) => b.length - a.length);
    const skipTags = new Set(['SCRIPT', 'STYLE', 'NOSCRIPT', 'TEXTAREA']);

    function translateText(text) {
        if (!text || !keys.length) return text;
        let translated = text;
        for (const key of keys) {
            translated = translated.split(key).join(phrases[key]);
        }
        return translated;
    }

    function translateElement(element) {
        if (!element || skipTags.has(element.tagName)) return;

        for (const attr of ['title', 'placeholder', 'aria-label', 'alt', 'data-label']) {
            if (element.hasAttribute && element.hasAttribute(attr)) {
                element.setAttribute(attr, translateText(element.getAttribute(attr)));
            }
        }

        if (
            element.tagName === 'INPUT'
            && ['button', 'submit', 'reset'].includes((element.getAttribute('type') || '').toLowerCase())
            && element.hasAttribute('value')
        ) {
            element.setAttribute('value', translateText(element.getAttribute('value')));
        }
    }

    function translateNode(node) {
        if (node.nodeType === Node.TEXT_NODE) {
            const parent = node.parentElement;
            if (parent && !skipTags.has(parent.tagName)) {
                const translated = translateText(node.nodeValue);
                if (translated !== node.nodeValue) node.nodeValue = translated;
            }
            return;
        }

        if (node.nodeType !== Node.ELEMENT_NODE || skipTags.has(node.tagName)) return;

        translateElement(node);
        node.childNodes.forEach(translateNode);
    }

    const nativeAlert = window.alert;
    const nativeConfirm = window.confirm;

    window.alert = function (message) {
        return nativeAlert.call(window, translateText(String(message)));
    };

    window.confirm = function (message) {
        return nativeConfirm.call(window, translateText(String(message)));
    };

    translateNode(document.body);

    new MutationObserver((mutations) => {
        for (const mutation of mutations) {
            if (mutation.type === 'characterData') {
                translateNode(mutation.target);
                continue;
            }

            mutation.addedNodes.forEach(translateNode);

            if (mutation.type === 'attributes') {
                translateElement(mutation.target);
            }
        }
    }).observe(document.body, {
        subtree: true,
        childList: true,
        characterData: true,
        attributes: true,
        attributeFilter: ['title', 'placeholder', 'aria-label', 'alt', 'data-label', 'value']
    });
})();
</script>
HTML;

        if (str_contains($html, '</body>')) {
            return str_replace('</body>', $script.'</body>', $html);
        }

        return $html.$script;
    }

    private function pairedTranslations(string $file): array
    {
        $source = trans($file, [], 'id');
        $target = trans($file, [], 'en');

        if (! is_array($source) || ! is_array($target)) {
            return [];
        }

        $phrases = [];

        foreach ($source as $key => $text) {
            if (is_string($text) && isset($target[$key]) && is_string($target[$key]) && $text !== $target[$key]) {
                $phrases[$text] = $target[$key];
            }
        }

        return $phrases;
    }
}
