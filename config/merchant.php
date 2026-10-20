<?php

/**
 * Google Merchant Center + storefront source of truth for NAP, shipping,
 * returns, currency and API credentials. Feed / JSON-LD / legal pages must
 * stay aligned with these values (CH market).
 */
return [

    'merchant_id' => env('MERCHANT_ID', env('GOOGLE_MERCHANT_ID', '')),
    'target_country' => env('MERCHANT_TARGET_COUNTRY', 'CH'),
    'content_language' => env('MERCHANT_CONTENT_LANGUAGE', 'de'),
    'currency' => env('MERCHANT_CURRENCY', 'CHF'),
    'feed_label' => env('MERCHANT_FEED_LABEL', env('MERCHANT_TARGET_COUNTRY', 'CH')),

    'default_brand' => env('MERCHANT_DEFAULT_BRAND', 'Heri Brennholz'),
    'feed_id_prefix' => 'hb-',

    /*
     | Set true only after every sale_price / regular_price pair on the
     | storefront has been human-verified against the live product page.
     */
    'reference_prices_verified' => (bool) env('MERCHANT_REFERENCE_PRICES_VERIFIED', true),

    /*
     | Merchant API (productInputs.insert).
     | Auth: OAuth (GOOGLE_CLIENT_*) OR service-account JSON.
     | https://developers.google.com/merchant/api/
     */
    'api' => [
        'enabled' => (bool) env('MERCHANT_API_ENABLED', false),
        'endpoint_version' => env('MERCHANT_API_VERSION', 'v1'),
        // Relative to storage/app/ (e.g. google/merchant-sa.json)
        'credentials' => env('MERCHANT_API_CREDENTIALS', env('GOOGLE_MERCHANT_CREDENTIALS', 'google/merchant-sa.json')),
        'timeout' => (int) env('MERCHANT_API_TIMEOUT', 30),
    ],

    'oauth' => [
        'client_id' => env('GOOGLE_CLIENT_ID', ''),
        'client_secret' => env('GOOGLE_CLIENT_SECRET', ''),
        'refresh_token' => env('GOOGLE_REFRESH_TOKEN', ''),
    ],

    /*
     | Name / Address / Phone — single source of truth for Impressum,
     | Kontakt, footer, JSON-LD Organization and Merchant Center business info.
     */
    'nap' => [
        'legal_name' => 'Heri Brennholz GmbH',
        'brand_name' => 'Heri Brennholz',
        'managing_director' => 'Markus Heri',
        'legal_form' => 'Gesellschaft mit beschränkter Haftung (GmbH)',
        'vat_id' => 'CHE-228.719.493',
        'tax_id' => 'CHE-228.719.493',
        'hr_number' => 'CH-241.4.020.905-9',
        'email' => 'kontakt@heribrennholzgmbh.com',
        'telephone' => '+41786099516',
        'telephone_display' => '+41 78 609 95 16',
        'street' => 'Fiderholzstrasse 7',
        'postal_code' => '4562',
        'locality' => 'Biberist',
        'region' => 'Solothurn',
        'country' => 'CH',
        'country_name' => 'Schweiz',
        'address_line' => 'Fiderholzstrasse 7, 4562 Biberist, Schweiz',
        'website' => env('APP_URL', 'https://heribrennholzgmbh.com'),
        'hours_label' => 'Montag–Freitag, 08:00–18:00 Uhr',
        'hours_schema' => [
            'Mo-Fr 08:00-18:00',
        ],
    ],

    'shipping' => [
        'country' => 'CH',
        'service' => env('MERCHANT_SHIPPING_SERVICE', 'Standardversand'),
        // Free shipping CH — must match Cart::shipping(), /versand and product pages.
        'price' => env('MERCHANT_SHIPPING_PRICE', '0.00'),
        'currency' => env('MERCHANT_CURRENCY', 'CHF'),
        'min_days' => (int) env('MERCHANT_SHIPPING_MIN_DAYS', 1),
        'max_days' => (int) env('MERCHANT_SHIPPING_MAX_DAYS', 2),
        // Coherent with storefront "1–2 Werktage": handling 0–1 + transit 1–2
        // (or handling 1–1 + transit 0–1). Keep min<=max. Feed reads these first.
        'handling_min' => (int) env('FEED_HANDLING_TIME_MIN', env('MERCHANT_HANDLING_MIN', 0)),
        'handling_max' => (int) env('FEED_HANDLING_TIME_MAX', env('MERCHANT_HANDLING_MAX', 1)),
        'transit_min' => (int) env('FEED_TRANSIT_TIME_MIN', env('MERCHANT_TRANSIT_MIN', 1)),
        'transit_max' => (int) env('FEED_TRANSIT_TIME_MAX', env('MERCHANT_TRANSIT_MAX', 2)),
        'zone_label' => 'gesamte Schweiz',
        'free_shipping' => true,
        'min_order_value' => 0,
        'policy_url' => '/versand',
    ],

    'returns' => [
        // Voluntary 14-day return right (CH has no statutory online withdrawal).
        'days' => (int) env('MERCHANT_RETURN_DAYS', 14),
        'customer_pays_return_shipping' => (bool) env('MERCHANT_CUSTOMER_PAYS_RETURN_SHIPPING', true),
        'return_shipping_cost' => env('MERCHANT_RETURN_SHIPPING_COST', '15.00'),
        'refund_days' => 14,
        'policy_url' => '/rueckgabe',
        'method' => 'by_mail',
        'return_country' => 'CH',
    ],

    'business_model' => [
        'description' => 'Online-Shop für Brennholz, Holzpellets, Holzbriketts und Zubehör. Verkauf und Lieferung ausschliesslich in der Schweiz.',
        'fulfillment' => 'Eigenversand und Spedition innerhalb der Schweiz',
        'customer_support' => 'E-Mail und Telefon, Mo–Fr 08:00–18:00',
    ],
];
