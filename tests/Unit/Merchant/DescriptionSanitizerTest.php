<?php

namespace Tests\Unit\Merchant;

use App\Domain\Merchant\Support\DescriptionSanitizer;
use Tests\TestCase;

class DescriptionSanitizerTest extends TestCase
{
    public function test_plain_rewrites_swiss_shipping_to_germany(): void
    {
        $plain = DescriptionSanitizer::plain(
            '<p>Holzpellets.</p><p>Lieferung innerhalb der Schweiz in 1 bis 2 Werktagen, kostenlos.</p>'
        );

        $this->assertStringContainsString('nach Deutschland', $plain);
        $this->assertStringContainsString('2 bis 5 Werktagen', $plain);
        $this->assertStringNotContainsString('Schweiz', $plain);
        $this->assertStringNotContainsString('1 bis 2', $plain);
    }

    public function test_html_keeps_markup_and_rewrites_shipping(): void
    {
        $html = DescriptionSanitizer::html(
            '<p>Holzpellets.</p><p>Lieferung innerhalb der Schweiz in 1 bis 2 Werktagen, kostenlos.</p>'
        );

        $this->assertStringContainsString('<p>', $html);
        $this->assertStringContainsString('Kostenlose Lieferung nach Deutschland', $html);
        $this->assertStringNotContainsString('Schweiz', $html);
    }
}
