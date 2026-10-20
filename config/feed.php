<?php

return [

    'brand' => env('MERCHANT_DEFAULT_BRAND', env('FEED_BRAND', 'Heri Brennholz')),

    'known_brands' => [
        'Invicta', 'La Nordica', 'FreePoint', 'Sannover', 'Pellini',
        'Edilkamin', 'Palazzetti', 'Scandic', 'Heri Brennholz',
    ],

    'default_condition' => 'new',

    'shipping_country' => env('MERCHANT_TARGET_COUNTRY', 'CH'),
    'shipping_price' => env('MERCHANT_SHIPPING_PRICE', '0.00'),
    'shipping_currency' => env('MERCHANT_CURRENCY', 'CHF'),

    // Plain service name only — delays go in min/max_handling_time + MC shipping settings.
    'shipping_service' => env('MERCHANT_SHIPPING_SERVICE', 'Standardversand'),

    'shipping_min_days' => (int) env('MERCHANT_SHIPPING_MIN_DAYS', 1),
    'shipping_max_days' => (int) env('MERCHANT_SHIPPING_MAX_DAYS', 2),

    // Prefer config/merchant.php shipping.handling_* as source of truth.
    // Defaults align with free CH delivery promise (1–2 Werktage total).
    'shipping_handling_time_min' => (int) env('FEED_HANDLING_TIME_MIN', env('MERCHANT_HANDLING_MIN', 0)),
    'shipping_handling_time_max' => (int) env('FEED_HANDLING_TIME_MAX', env('MERCHANT_HANDLING_MAX', 1)),
    'shipping_transit_time_min' => (int) env('FEED_TRANSIT_TIME_MIN', 0),
    'shipping_transit_time_max' => (int) env('FEED_TRANSIT_TIME_MAX', 1),

    'google_product_category' => env('FEED_GOOGLE_CATEGORY', '6229'),

    'google_product_category_map' => [
        'brennholz' => '6229',
        'pellets' => '6229',
        'briketts' => '6229',
        'kaminholz' => '6229',
        'anzuender' => '6229',
        'zubehoer' => '6229',
        'oefen' => '606',
        'kaminoefen' => '606',
        'kamineinsaetze' => '6431',
    ],

    'content_language' => env('MERCHANT_CONTENT_LANGUAGE', env('FEED_CONTENT_LANGUAGE', 'de')),
    'target_country' => env('MERCHANT_TARGET_COUNTRY', env('FEED_TARGET_COUNTRY', 'CH')),
    'feed_label' => env('MERCHANT_FEED_LABEL', env('MERCHANT_TARGET_COUNTRY', 'CH')),

    'token' => env('MERCHANT_FEED_TOKEN', ''),
    'cache_ttl' => (int) env('FEED_CACHE_TTL', 900),

    // Refuse to publish / overwrite feeds below this absolute item count.
    'min_items' => (int) env('FEED_MIN_ITEMS', 1),

    'title_suffix' => env('FEED_TITLE_SUFFIX', ''),

    /*
     | Mirrors merchant.nap — kept for backward compatibility with views/JSON-LD.
     | Prefer company_nap() / config('merchant.nap') in new code.
     */
    'company' => [
        'legal_name' => 'Heri Brennholz GmbH',
        'street' => 'Fiderholzstrasse 7',
        'postal_code' => '4562',
        'city' => 'Biberist',
        'country' => 'CH',
        'country_name' => 'Schweiz',
        'region' => 'Solothurn',
        'phone' => '+41 78 609 95 16',
        'telephone' => '+41786099516',
        'email' => 'kontakt@heribrennholzgmbh.com',
        'uid' => 'CHE-228.719.493',
        'hr' => 'CH-241.4.020.905-9',
        'website' => env('APP_URL', 'https://heribrennholzgmbh.com'),
        'hours' => 'Montag–Freitag, 08:00–18:00 Uhr',
    ],
];
