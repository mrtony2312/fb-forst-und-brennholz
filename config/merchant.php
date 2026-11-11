<?php

/**
 * Google Merchant Center + storefront source of truth for NAP, shipping,
 * returns, currency and API credentials. Feed / JSON-LD / legal pages must
 * stay aligned with these values (DE market, EUR).
 */
return [

    'merchant_id' => env('MERCHANT_ID', env('GOOGLE_MERCHANT_ID', '')),
    'target_country' => env('MERCHANT_TARGET_COUNTRY', 'DE'),
    'content_language' => env('MERCHANT_CONTENT_LANGUAGE', 'de'),
    'currency' => env('MERCHANT_CURRENCY', 'EUR'),
    'feed_label' => env('MERCHANT_FEED_LABEL', env('MERCHANT_TARGET_COUNTRY', 'DE')),

    'default_brand' => env('MERCHANT_DEFAULT_BRAND', 'F & B Forst und Brennholz'),
    'feed_id_prefix' => 'hb-',

    /*
     | Set true only after every sale_price / regular_price pair on the
     | storefront has been human-verified against the live product page.
     */
    'reference_prices_verified' => (bool) env('MERCHANT_REFERENCE_PRICES_VERIFIED', true),

    'api' => [
        'enabled' => (bool) env('MERCHANT_API_ENABLED', false),
        'endpoint_version' => env('MERCHANT_API_VERSION', 'v1'),
        'credentials' => env('MERCHANT_API_CREDENTIALS', env('GOOGLE_MERCHANT_CREDENTIALS', 'google/merchant-sa.json')),
        'timeout' => (int) env('MERCHANT_API_TIMEOUT', 30),
    ],

    'oauth' => [
        'client_id' => env('GOOGLE_CLIENT_ID', ''),
        'client_secret' => env('GOOGLE_CLIENT_SECRET', ''),
        'refresh_token' => env('GOOGLE_REFRESH_TOKEN', ''),
    ],

    /*
     | Seller: F & B Forst und Brennholz GmbH (DE). Market / shipping destination = DE.
     */
    'nap' => [
        'legal_name' => 'F & B Forst und Brennholz GmbH',
        'brand_name' => 'F & B Forst und Brennholz',
        'managing_director' => env('SHOP_MANAGING_DIRECTOR', 'Thomas Josef Freimann, Tobias Heinrich Boßle'),
        'managing_directors' => [
            [
                'name' => 'Thomas Josef Freimann',
                'role' => 'Geschäftsführer',
                'share' => '50 %',
            ],
            [
                'name' => 'Tobias Heinrich Boßle',
                'role' => 'Geschäftsführer',
                'share' => '50 %',
            ],
        ],
        'legal_form' => 'Gesellschaft mit beschränkter Haftung (GmbH)',
        'vat_id' => env('SHOP_VAT_ID', ''),
        'tax_id' => env('SHOP_TAX_ID', ''),
        'hr_number' => 'HRB 40744',
        'register_court' => 'Amtsgericht Nürnberg',
        'european_id' => 'DE-D3310V.HRB40744',
        'share_capital' => '25.100 €',
        'incorporated_at' => '2022-08-02',
        'email' => env('SHOP_CONTACT_EMAIL', 'info@fbforstundbrennholzgmbh.com'),
        'telephone' => env('SHOP_CONTACT_PHONE', '+4915255641834'),
        'telephone_display' => env('SHOP_CONTACT_PHONE_DISPLAY', '+49 1525 5641834'),
        'street' => 'Dekan-Schnitter-Straße 4',
        'postal_code' => '92366',
        'locality' => 'Hohenfels',
        'region' => 'Bayern',
        'country' => 'DE',
        'country_name' => 'Deutschland',
        'address_line' => 'Dekan-Schnitter-Straße 4, 92366 Hohenfels, Deutschland',
        // Sitz = registered office; operating_address = Lager / Betriebsstätte (2024).
        'operating_address' => [
            'street' => 'Haarziegelhütte 10a',
            'postal_code' => '92366',
            'locality' => 'Hohenfels',
            'region' => 'Bayern',
            'country' => 'DE',
            'country_name' => 'Deutschland',
            'address_line' => 'Haarziegelhütte 10a, 92366 Hohenfels, Deutschland',
            'label' => 'Betriebsstätte / Lager',
        ],
        'website' => env('APP_URL', 'https://fbforstundbrennholzgmbh.com'),
        'hours_label' => 'Montag–Freitag, 08:00–18:00 Uhr',
        'hours_schema' => [
            'Mo-Fr 08:00-18:00',
        ],
    ],

    'shipping' => [
        'country' => 'DE',
        'service' => env('MERCHANT_SHIPPING_SERVICE', 'Standardversand'),
        'price' => env('MERCHANT_SHIPPING_PRICE', '0.00'),
        'currency' => env('MERCHANT_CURRENCY', 'EUR'),
        'min_days' => (int) env('MERCHANT_SHIPPING_MIN_DAYS', 2),
        'max_days' => (int) env('MERCHANT_SHIPPING_MAX_DAYS', 5),
        'handling_min' => (int) env('FEED_HANDLING_TIME_MIN', env('MERCHANT_HANDLING_MIN', 1)),
        'handling_max' => (int) env('FEED_HANDLING_TIME_MAX', env('MERCHANT_HANDLING_MAX', 2)),
        'transit_min' => (int) env('FEED_TRANSIT_TIME_MIN', env('MERCHANT_TRANSIT_MIN', 1)),
        'transit_max' => (int) env('FEED_TRANSIT_TIME_MAX', env('MERCHANT_TRANSIT_MAX', 3)),
        'zone_label' => 'Deutschland',
        'free_shipping' => true,
        'min_order_value' => 0,
        'policy_url' => '/versand',
    ],

    'returns' => [
        // EU/DE statutory 14-day withdrawal for online purchases.
        'days' => (int) env('MERCHANT_RETURN_DAYS', 14),
        'customer_pays_return_shipping' => (bool) env('MERCHANT_CUSTOMER_PAYS_RETURN_SHIPPING', true),
        'return_shipping_cost' => env('MERCHANT_RETURN_SHIPPING_COST', '19.90'),
        'refund_days' => 14,
        'policy_url' => '/rueckgabe',
        'method' => 'by_mail',
        'return_country' => 'DE',
    ],

    'business_model' => [
        'description' => 'Handel mit Brennholz und Holz (Brennholzhandel / Holzhandel) sowie Online-Shop für Brennholz, Holzpellets, Holzbriketts und Zubehör. Verkauf und Lieferung in Deutschland.',
        'activities' => [
            'Brennholzhandel',
            'Holzhandel',
        ],
        'fulfillment' => 'Eigenversand und Spedition in Deutschland',
        'customer_support' => 'E-Mail und Telefon, Mo–Fr 08:00–18:00',
    ],
];
