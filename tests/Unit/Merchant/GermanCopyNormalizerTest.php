<?php

namespace Tests\Unit\Merchant;

use App\Domain\Merchant\Support\GermanCopyNormalizer;
use Tests\TestCase;

class GermanCopyNormalizerTest extends TestCase
{
    public function test_normalize_converts_swiss_spelling_to_german(): void
    {
        $text = GermanCopyNormalizer::normalize('Eine gleichmässige Wärme, Weiss lackiert, reissfest.');

        $this->assertSame('Eine gleichmäßige Wärme, Weiß lackiert, reißfest.', $text);
    }

    public function test_normalize_translates_leftover_french_fragments(): void
    {
        $text = GermanCopyNormalizer::normalize('Herkunft: Pays Bas. Disponible en stock.');

        $this->assertSame('Herkunft: Niederlande. Auf Lager.', $text);
    }

    public function test_normalize_keeps_unrelated_text_unchanged(): void
    {
        $text = 'Holzpellets aus Nadelholz, 15 kg Säcke.';

        $this->assertSame($text, GermanCopyNormalizer::normalize($text));
    }
}
