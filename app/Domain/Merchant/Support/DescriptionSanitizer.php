<?php

namespace App\Domain\Merchant\Support;

use Illuminate\Support\Str;

class DescriptionSanitizer
{
    /**
     * Strip HTML and normalize DE shipping / market claims for the Merchant feed.
     */
    public static function plain(?string $html, string $fallback = ''): string
    {
        $text = html_entity_decode(strip_tags((string) $html), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = preg_replace('/https?:\/\/\s*\S+/iu', '', $text) ?? $text;
        $text = preg_replace('/\s+/u', ' ', $text) ?? $text;
        $text = trim(self::rewriteMarketClaims($text));

        $suffix = self::shippingSuffix();

        if ($text === '') {
            $text = trim($fallback);
        }

        if ($text !== '' && ! str_contains(mb_strtolower($text), 'nach deutschland')) {
            $text = trim($text).$suffix;
        }

        if ($text === '') {
            $text = trim('Brennholz und Holzpellets –'.$suffix);
        }

        $text = preg_replace('/\s+/u', ' ', $text) ?? $text;

        return Str::limit(trim($text), 1500, '');
    }

    /**
     * Keep safe HTML tags while rewriting CH shipping claims for PDP / crawlers.
     */
    public static function html(?string $html): string
    {
        $html = (string) $html;
        if ($html === '') {
            return '';
        }

        $allowed = '<p><br><ul><ol><li><strong><em><b><i>';
        $html = strip_tags($html, $allowed);
        $html = self::rewriteMarketClaims($html);

        return trim($html);
    }

    public static function rewriteMarketClaims(string $text): string
    {
        $min = (int) config('merchant.shipping.min_days', 2);
        $max = (int) config('merchant.shipping.max_days', 5);
        $deShipping = sprintf('Kostenlose Lieferung nach Deutschland in %d bis %d Werktagen.', $min, $max);

        $replacements = [
            '/Lieferung innerhalb der Schweiz in \d+ bis \d+ Werktagen,?\s*kostenlos\.?/iu' => $deShipping,
            '/Kostenlose Lieferung auf Palette in die Schweiz und nach Deutschland\.?/iu' => $deShipping,
            '/Kostenlose Lieferung (in die|innerhalb der) Schweiz[^.]*\.?/iu' => $deShipping,
            '/in die Schweiz und nach (Deutschland|Liechtenstein)/iu' => 'nach Deutschland',
            '/innerhalb der Schweiz/iu' => 'nach Deutschland',
            '/in der ganzen Schweiz/iu' => 'nach Deutschland',
            '/in der Schweiz/iu' => 'nach Deutschland',
            '/sowie nach Liechtenstein/iu' => '',
            '/und nach Liechtenstein/iu' => '',
            '/\bnach Liechtenstein\b/iu' => '',
            '/\bSchweizer\b/iu' => 'deutsche',
            '/\bSchweiz\b/iu' => 'Deutschland',
            '/\bLiechtenstein\b/iu' => 'Deutschland',
            '/Lieferung in 1 bis 2 Werktagen[^.]*\.?/iu' => $deShipping,
            '/Lieferung in 2 bis 4 Werktagen[^.]*\.?/iu' => $deShipping,
            '/Bearbeitungszeit[^.<]{0,80}\./iu' => '',
            '/\b(guérit|miracle|perte de poids garantie|replica|1:1|authentic AAA)\b/iu' => '',
        ];

        foreach ($replacements as $pattern => $replacement) {
            $text = preg_replace($pattern, $replacement, $text) ?? $text;
        }

        // Collapse duplicate shipping sentences after repeated rewrites.
        $text = preg_replace(
            '/(Kostenlose Lieferung nach Deutschland in \d+ bis \d+ Werktagen\.)(?:\s*\1)+/iu',
            '$1',
            $text
        ) ?? $text;

        return $text;
    }

    private static function shippingSuffix(): string
    {
        $min = (int) config('merchant.shipping.min_days', 2);
        $max = (int) config('merchant.shipping.max_days', 5);

        return sprintf(' Kostenlose Lieferung nach Deutschland in %d bis %d Werktagen.', $min, $max);
    }
}
