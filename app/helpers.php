<?php

use App\Models\Product;

if (! function_exists('shop_currency')) {
    function shop_currency(): string
    {
        $code = strtoupper((string) config('shop.currency', config('merchant.currency', 'EUR')));

        return $code !== '' ? $code : 'EUR';
    }
}

if (! function_exists('shop_money')) {
    /**
     * Format a monetary value for the DE storefront (German grouping).
     * Example: EUR 1.234,56
     */
    function shop_money(float|int|string|null $amount, ?string $currency = null): string
    {
        $currency = strtoupper((string) ($currency ?: shop_currency()));
        $formatted = number_format((float) $amount, 2, ',', '.');

        return trim($currency.' '.$formatted);
    }
}

if (! function_exists('vat_included')) {
    /** Extract MWST amount from a gross (brutto) price. */
    function vat_included(float|int|string|null $gross, ?float $rate = null): float
    {
        $rate ??= (float) config('shop.vat_rate', 19.0);

        return round((float) $gross * $rate / (100 + $rate), 2);
    }
}

if (! function_exists('payment_methods')) {
    /** @return array<string, string> aktive Zahlungsarten: Schlüssel => Bezeichnung */
    function payment_methods(): array
    {
        return collect(config('shop.payment_methods', []))
            ->filter(fn ($m) => $m['enabled'] ?? false)
            ->map(fn ($m) => $m['label'])
            ->all();
    }
}

if (! function_exists('payment_methods_text')) {
    function payment_methods_text(): string
    {
        return implode(', ', payment_methods());
    }
}

if (! function_exists('company_nap')) {
    /**
     * Unified NAP / business identity for storefront + Merchant Center.
     *
     * @return array<string, mixed>
     */
    function company_nap(): array
    {
        $nap = config('merchant.nap', []);
        $feed = config('feed.company', []);

        return array_merge([
            'legal_name' => $feed['legal_name'] ?? 'F & B Forst und Brennholz GmbH',
            'brand_name' => 'F & B Forst und Brennholz',
            'street' => $feed['street'] ?? 'Dekan-Schnitter-Straße 4',
            'postal_code' => $feed['postal_code'] ?? '92366',
            'locality' => $feed['city'] ?? 'Hohenfels',
            'city' => $feed['city'] ?? 'Hohenfels',
            'region' => $feed['region'] ?? 'Bayern',
            'country' => $feed['country'] ?? 'DE',
            'country_name' => $feed['country_name'] ?? 'Deutschland',
            'email' => $feed['email'] ?? 'info@fbforstundbrennholzgmbh.com',
            'telephone' => $feed['telephone'] ?? '+4915255641834',
            'telephone_display' => $feed['phone'] ?? '+49 1525 5641834',
            'operating_address' => [
                'street' => $feed['operating_street'] ?? 'Haarziegelhütte 10a',
                'postal_code' => $feed['operating_postal_code'] ?? '92366',
                'locality' => $feed['operating_city'] ?? 'Hohenfels',
                'country_name' => $feed['country_name'] ?? 'Deutschland',
                'address_line' => trim(($feed['operating_street'] ?? 'Haarziegelhütte 10a').', '.($feed['operating_postal_code'] ?? '92366').' '.($feed['operating_city'] ?? 'Hohenfels').', '.($feed['country_name'] ?? 'Deutschland')),
                'label' => 'Betriebsstätte / Lager',
            ],
            'vat_id' => $feed['uid'] ?? '',
            'hr_number' => $feed['hr'] ?? 'HRB 40744',
            'register_court' => $feed['register_court'] ?? 'Amtsgericht Nürnberg',
            'european_id' => $feed['european_id'] ?? 'DE-D3310V.HRB40744',
            'share_capital' => $feed['share_capital'] ?? '25.100 €',
            'website' => $feed['website'] ?? config('app.url'),
            'hours_label' => $feed['hours'] ?? 'Montag–Freitag, 08:00–18:00 Uhr',
            'address_line' => trim(
                ($feed['street'] ?? 'Dekan-Schnitter-Straße 4').', '.
                ($feed['postal_code'] ?? '92366').' '.
                ($feed['city'] ?? 'Hohenfels').', '.
                ($feed['country_name'] ?? 'Deutschland')
            ),
        ], $nap);
    }
}

if (! function_exists('shipping_summary')) {
    /** Human-readable shipping promise — must match feed + /versand. */
    function shipping_summary(): string
    {
        $min = (int) config('merchant.shipping.min_days', config('shop.shipping.min_days', 1));
        $max = (int) config('merchant.shipping.max_days', config('shop.shipping.max_days', 2));

        $zone = 'nach Deutschland';

        if ((bool) config('merchant.shipping.free_shipping', true)) {
            return sprintf('Kostenlose Lieferung %s in %d–%d Werktagen', $zone, $min, $max);
        }

        return sprintf('Lieferung %s in %d–%d Werktagen', $zone, $min, $max);
    }
}

if (! function_exists('return_summary')) {
    function return_summary(): string
    {
        $days = (int) config('merchant.returns.days', 14);

        return sprintf(
            '14-tägiges Widerrufsrecht für Verbraucher (EU/DE) – Rückgabe innert %d Tagen',
            $days
        );
    }
}

if (! function_exists('gmc_reference_prices_verified')) {
    function gmc_reference_prices_verified(): bool
    {
        return (bool) config('merchant.reference_prices_verified', false);
    }
}

if (! function_exists('gmc_product_on_promo')) {
    function gmc_product_on_promo(?Product $product): bool
    {
        if (! $product || ! gmc_reference_prices_verified()) {
            return false;
        }

        $regular = (float) ($product->regular_price ?? 0);
        $price = (float) ($product->price ?? 0);

        return (bool) ($product->on_sale ?? false)
            && $regular > 0
            && $price > 0
            && $price < $regular;
    }
}

if (! function_exists('gmc_absolute_url')) {
    /**
     * Absolute product/image URL for GMC. Prefer https (Merchant requirement).
     */
    function gmc_absolute_url(?string $url): string
    {
        $url = trim((string) $url);

        if ($url === '') {
            return '';
        }

        if (str_starts_with($url, '//')) {
            $url = 'https:'.$url;
        }

        if (! str_starts_with($url, 'http://') && ! str_starts_with($url, 'https://')) {
            $url = rtrim((string) config('app.url'), '/').'/'.ltrim($url, '/');
        }

        // If the storefront is https, never emit http links/images to GMC.
        if (str_starts_with(strtolower($url), 'http://')
            && str_starts_with(strtolower((string) config('app.url')), 'https://')) {
            $url = 'https://'.substr($url, 7);
        }

        return $url;
    }
}

if (! function_exists('media_url')) {
    function media_url(?string $path): string
    {
        if ($path === null || $path === '') {
            return '';
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        // Root-relative: works on 127.0.0.1, localhost and production without APP_URL mismatches.
        return '/'.ltrim(str_replace('\\', '/', $path), '/');
    }
}
