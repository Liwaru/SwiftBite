<?php

namespace Tests\Feature;

use App\Http\Middleware\TranslateHtml;
use Tests\TestCase;

class LanguageSwitchTest extends TestCase
{
    public function test_html_text_and_ui_attributes_are_translated_when_locale_is_english(): void
    {
        app()->setLocale('en');

        $middleware = app(TranslateHtml::class);
        $response = $middleware->handle(
            request(),
            fn () => response('<body><button title="Pilih Bahasa" data-label="Aksi">Data Master</button></body>')
        );

        $content = $response->getContent();

        $this->assertStringContainsString('<button title="Select Language" data-label="Action">Master Data</button>', $content);
        $this->assertStringContainsString('MutationObserver', $content);
        $this->assertStringContainsString('window.alert', $content);
    }

    public function test_form_values_are_not_translated_unless_they_are_visible_input_buttons(): void
    {
        app()->setLocale('en');

        $middleware = app(TranslateHtml::class);
        $response = $middleware->handle(
            request(),
            fn () => response('<body><option value="Makanan">Makanan</option><input type="submit" value="Simpan"></body>')
        );

        $content = $response->getContent();

        $this->assertStringContainsString('<option value="Makanan">Foods</option>', $content);
        $this->assertStringContainsString('<input type="submit" value="Save">', $content);
    }

    public function test_language_switch_stores_locale_cookie(): void
    {
        $response = $this->from('/manager')->get(route('language.switch', 'en'));

        $response->assertRedirect('/manager');
        $response->assertCookie('swiftbite_locale', 'en');
    }
}
