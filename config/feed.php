<?php

return [

    'title' => env('FEED_TITLE', 'F & B Forst und Brennholz – Google Merchant Feed (DE)'),
    'description' => env('FEED_DESCRIPTION', 'Produktfeed für Google Merchant Center: Deutschland, Deutsch, EUR.'),

    'brand' => env('MERCHANT_DEFAULT_BRAND', env('FEED_BRAND', 'F & B Forst und Brennholz')),

    'known_brands' => [
        'Invicta', 'La Nordica', 'FreePoint', 'Sannover', 'Pellini',
        'Edilkamin', 'Palazzetti', 'Scandic', 'Heri Brennholz',
        'F & B Forst und Brennholz',
    ],

    'default_condition' => 'new',
    'condition' => env('FEED_CONDITION', 'new'),

    'shipping_country' => env('MERCHANT_TARGET_COUNTRY', 'DE'),
    'shipping_price' => env('MERCHANT_SHIPPING_PRICE', '0.00'),
    'shipping_currency' => env('MERCHANT_CURRENCY', 'EUR'),

    // Plain service name only — delays go in min/max_handling_time + MC shipping settings.
    'shipping_service' => env('MERCHANT_SHIPPING_SERVICE', 'Standardversand'),

    'shipping_min_days' => (int) env('MERCHANT_SHIPPING_MIN_DAYS', 2),
    'shipping_max_days' => (int) env('MERCHANT_SHIPPING_MAX_DAYS', 5),

    // Prefer config/merchant.php shipping.handling_* as source of truth.
    'shipping_handling_time_min' => (int) env('FEED_HANDLING_TIME_MIN', env('MERCHANT_HANDLING_MIN', 1)),
    'shipping_handling_time_max' => (int) env('FEED_HANDLING_TIME_MAX', env('MERCHANT_HANDLING_MAX', 2)),
    'shipping_transit_time_min' => (int) env('FEED_TRANSIT_TIME_MIN', 1),
    'shipping_transit_time_max' => (int) env('FEED_TRANSIT_TIME_MAX', 3),

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
    'target_country' => env('MERCHANT_TARGET_COUNTRY', env('FEED_TARGET_COUNTRY', 'DE')),
    'feed_label' => env('MERCHANT_FEED_LABEL', env('MERCHANT_TARGET_COUNTRY', 'DE')),

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
        'legal_name' => 'F & B Forst und Brennholz GmbH',
        'street' => 'Dekan-Schnitter-Straße 4',
        'postal_code' => '92366',
        'city' => 'Hohenfels',
        'country' => 'DE',
        'country_name' => 'Deutschland',
        'region' => 'Bayern',
        'phone' => env('SHOP_CONTACT_PHONE_DISPLAY', '+49 1525 5641834'),
        'telephone' => env('SHOP_CONTACT_PHONE', '+4915255641834'),
        'email' => env('SHOP_CONTACT_EMAIL', 'info@fbforstundbrennholzgmbh.com'),
        'uid' => env('SHOP_VAT_ID', ''),
        'hr' => 'HRB 40744',
        'register_court' => 'Amtsgericht Nürnberg',
        'european_id' => 'DE-D3310V.HRB40744',
        'share_capital' => '25.100 €',
        'website' => env('APP_URL', 'https://fbforstundbrennholzgmbh.com'),
        'hours' => 'Montag–Freitag, 08:00–18:00 Uhr',
        'operating_street' => 'Haarziegelhütte 10a',
        'operating_postal_code' => '92366',
        'operating_city' => 'Hohenfels',
    ],
];
