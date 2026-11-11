<?php

return [

    // German VAT standard rate (MwSt). Catalog prices are gross (brutto, inkl. MwSt).
    'vat_rate' => (float) env('SHOP_VAT_RATE', 19.0),
    'vat_label' => 'MwSt',

    // ISO 4217 — must match Merchant feed + JSON-LD + checkout.
    'currency' => strtoupper((string) env('MERCHANT_CURRENCY', env('SHOP_CURRENCY', 'EUR'))),

    /*
     | Legacy one-shot FX marker (catalog already stored in EUR).
     | Kept for migration tooling only — not shown on the storefront.
     */
    'fx' => [
        'from' => 'EUR',
        'to' => 'EUR',
        'rate' => (float) env('SHOP_FX_CHF_EUR', 1.093),
        'as_of' => '2026-11-09',
    ],

    // Lieferung DE — muss mit config/merchant.php shipping und Cart::shipping() übereinstimmen.
    'shipping' => [
        'country' => 'DE',
        'free' => true,
        'price' => 0.0,
        'min_days' => (int) env('MERCHANT_SHIPPING_MIN_DAYS', 2),
        'max_days' => (int) env('MERCHANT_SHIPPING_MAX_DAYS', 5),
        'label' => 'Kostenlose Lieferung nach Deutschland',
    ],

    // Zahlungsarten. NUR aktivieren, was tatsächlich abgewickelt werden kann.
    'payment_methods' => [
        'rechnung' => [
            'label' => 'Kauf auf Rechnung',
            'enabled' => true,
            'hint' => 'Sie erhalten die Rechnung per E-Mail. Zahlbar innert 10 Tagen.',
        ],
        'vorkasse' => [
            'label' => 'Vorkasse (SEPA-Überweisung)',
            'enabled' => true,
            'hint' => 'Wir senden Ihnen die Zahlungsdaten per E-Mail. Versand nach Zahlungseingang.',
        ],
        'twint' => [
            'label' => 'TWINT',
            'enabled' => false,
            'hint' => '',
        ],
        'karte' => [
            'label' => 'Kreditkarte (Visa, Mastercard)',
            'enabled' => (bool) env('SHOP_PAY_CARD', false),
            'hint' => 'Sichere Kartenzahlung über unseren Zahlungsanbieter.',
        ],
        'postfinance' => [
            'label' => 'PostFinance',
            'enabled' => false,
            'hint' => '',
        ],
    ],

    'checkout' => [
        'only_germany' => true,
        'require_phone' => true,
        'company_visible' => true,
    ],
];
