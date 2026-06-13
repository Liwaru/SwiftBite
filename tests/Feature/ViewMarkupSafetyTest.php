<?php

namespace Tests\Feature;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use Tests\TestCase;

class ViewMarkupSafetyTest extends TestCase
{
    public function test_blade_views_do_not_contain_script_like_text_outside_script_blocks(): void
    {
        $issues = [];

        foreach ($this->bladeFiles() as $file) {
            $insideScript = false;
            $insideStyle = false;
            $lines = file($file->getPathname()) ?: [];

            foreach ($lines as $index => $line) {
                $lineNumber = $index + 1;
                $trimmed = trim($line);

                if (str_contains($trimmed, '<script')) {
                    $insideScript = true;
                }

                if (str_contains($trimmed, '<style')) {
                    $insideStyle = true;
                }

                if (
                    ! $insideScript
                    && ! $insideStyle
                    && $this->looksLikeLeakedJavaScript($trimmed)
                    && ! $this->isExpectedBladeOrMarkup($trimmed)
                ) {
                    $issues[] = $this->formatIssue($file, $lineNumber, $trimmed);
                }

                if (str_contains($trimmed, '</script>')) {
                    $insideScript = false;
                }

                if (str_contains($trimmed, '</style>')) {
                    $insideStyle = false;
                }
            }

            if ($insideScript) {
                $issues[] = $this->formatIssue($file, count($lines), 'Unclosed <script> block');
            }

            if ($insideStyle) {
                $issues[] = $this->formatIssue($file, count($lines), 'Unclosed <style> block');
            }
        }

        $this->assertSame([], $issues);
    }

    public function test_blade_includes_are_not_embedded_inside_script_blocks(): void
    {
        $issues = [];

        foreach ($this->bladeFiles() as $file) {
            $insideScript = false;
            $lines = file($file->getPathname()) ?: [];

            foreach ($lines as $index => $line) {
                $lineNumber = $index + 1;
                $trimmed = trim($line);

                if (str_contains($trimmed, '<script')) {
                    $insideScript = true;
                }

                if ($insideScript && str_contains($trimmed, '@include')) {
                    $issues[] = $this->formatIssue($file, $lineNumber, $trimmed);
                }

                if (str_contains($trimmed, '</script>')) {
                    $insideScript = false;
                }
            }
        }

        $this->assertSame([], $issues);
    }

    /**
     * @return iterable<SplFileInfo>
     */
    private function bladeFiles(): iterable
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(resource_path('views'))
        );

        foreach ($iterator as $file) {
            if ($file instanceof SplFileInfo && $file->isFile() && str_ends_with($file->getFilename(), '.blade.php')) {
                yield $file;
            }
        }
    }

    private function looksLikeLeakedJavaScript(string $line): bool
    {
        if ($line === '') {
            return false;
        }

        return (bool) preg_match('/^(const|let|var|function|async function)\s|document\.|window\.|setTimeout\(|console\.|await\s/', $line);
    }

    private function isExpectedBladeOrMarkup(string $line): bool
    {
        return str_starts_with($line, '@')
            || str_starts_with($line, '$')
            || str_starts_with($line, '//')
            || str_starts_with($line, '{{--')
            || str_starts_with($line, '<')
            || str_starts_with($line, '}');
    }

    private function formatIssue(SplFileInfo $file, int $line, string $text): string
    {
        $relativePath = str_replace(base_path() . DIRECTORY_SEPARATOR, '', $file->getPathname());

        return $relativePath . ':' . $line . ' ' . $text;
    }
}
