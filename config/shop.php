<?php

return [

    // Schweizer Mehrwertsteuer-Normalsatz (seit 1.1.2024: 8.1 %). Brennholz, Pellets, Briketts
    // und Öfen unterliegen dem Normalsatz. Alle Shop-Preise sind Bruttopreise (inkl. MWST).
    'vat_rate' => (float) env('SHOP_VAT_RATE', 8.1),

    // Lieferung CH — muss mit config/merchant.php shipping und Cart::shipping() übereinstimmen.
    'shipping' => [
        'country' => 'CH',
        'free' => true,
        'price' => 0.0,
        'min_days' => (int) env('MERCHANT_SHIPPING_MIN_DAYS', 1),
        'max_days' => (int) env('MERCHANT_SHIPPING_MAX_DAYS', 2),
        'label' => 'Kostenlose Lieferung in der ganzen Schweiz',
    ],

    // Zahlungsarten. NUR aktivieren, was tatsächlich abgewickelt werden kann.
    // TWINT, Kreditkarte und PostFinance setzen einen Vertrag mit einem Zahlungsanbieter
    // (PSP) und eine technische Anbindung voraus – erst dann auf true setzen.
    'payment_methods' => [
        'rechnung' => [
            'label' => 'Kauf auf Rechnung (QR-Rechnung)',
            'enabled' => true,
            'hint' => 'Sie erhalten die QR-Rechnung per E-Mail. Zahlbar innert 10 Tagen.',
        ],
        'vorkasse' => [
            'label' => 'Vorkasse (Banküberweisung)',
            'enabled' => true,
            'hint' => 'Wir senden Ihnen die Zahlungsdaten per E-Mail. Versand nach Zahlungseingang.',
        ],
        'twint' => [
            'label' => 'TWINT',
            'enabled' => (bool) env('SHOP_PAY_TWINT', false),
            'hint' => 'Zahlung per TWINT nach Bestellbestätigung.',
        ],
        'karte' => [
            'label' => 'Kreditkarte (Visa, Mastercard)',
            'enabled' => (bool) env('SHOP_PAY_CARD', false),
            'hint' => 'Sichere Kartenzahlung über unseren Zahlungsanbieter.',
        ],
        'postfinance' => [
            'label' => 'PostFinance',
            'enabled' => (bool) env('SHOP_PAY_POSTFINANCE', false),
            'hint' => 'Zahlung via PostFinance.',
        ],
    ],

    'checkout' => [
        'only_switzerland' => true,
        'require_phone' => true,
        'company_visible' => true,
    ],
];
