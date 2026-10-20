<?php

if (! function_exists('swiss_money')) {
    /**
     * Format a monetary value using Swiss conventions: apostrophe as the
     * thousands separator, a dot as the decimal separator, prefixed with CHF.
     */
    function swiss_money(float|int|string|null $amount, string $currency = 'CHF'): string
    {
        $formatted = number_format((float) $amount, 2, '.', "'");

        return trim($currency.' '.$formatted);
    }
}

if (! function_exists('vat_included')) {
    /** Extract MWST amount from a gross (brutto) price. */
    function vat_included(float|int|string|null $gross, ?float $rate = null): float
    {
        $rate ??= (float) config('shop.vat_rate', 8.1);

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
            'legal_name' => $feed['legal_name'] ?? 'Heri Brennholz GmbH',
            'brand_name' => 'Heri Brennholz',
            'street' => $feed['street'] ?? 'Fiderholzstrasse 7',
            'postal_code' => $feed['postal_code'] ?? '4562',
            'locality' => $feed['city'] ?? 'Biberist',
            'city' => $feed['city'] ?? 'Biberist',
            'region' => $feed['region'] ?? 'Solothurn',
            'country' => $feed['country'] ?? 'CH',
            'country_name' => $feed['country_name'] ?? 'Schweiz',
            'email' => $feed['email'] ?? 'kontakt@heribrennholzgmbh.com',
            'telephone' => $feed['telephone'] ?? '+41786099516',
            'telephone_display' => $feed['phone'] ?? '+41 78 609 95 16',
            'vat_id' => $feed['uid'] ?? 'CHE-228.719.493',
            'hr_number' => $feed['hr'] ?? 'CH-241.4.020.905-9',
            'website' => $feed['website'] ?? config('app.url'),
            'hours_label' => $feed['hours'] ?? 'Montag–Freitag, 08:00–18:00 Uhr',
            'address_line' => trim(
                ($feed['street'] ?? 'Fiderholzstrasse 7').', '.
                ($feed['postal_code'] ?? '4562').' '.
                ($feed['city'] ?? 'Biberist').', '.
                ($feed['country_name'] ?? 'Schweiz')
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

        if ((bool) config('merchant.shipping.free_shipping', true)) {
            return sprintf(
                'Kostenlose Lieferung in der ganzen Schweiz in %d–%d Werktagen',
                $min,
                $max
            );
        }

        return sprintf('Lieferung in der Schweiz in %d–%d Werktagen', $min, $max);
    }
}

if (! function_exists('return_summary')) {
    function return_summary(): string
    {
        $days = (int) config('merchant.returns.days', 14);

        return sprintf(
            'Freiwilliges Rückgaberecht innert %d Tagen (CH hat kein gesetzliches Online-Widerrufsrecht)',
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
    function gmc_product_on_promo(?\App\Models\Product $product): bool
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

        return asset(ltrim($path, '/'));
    }
}
