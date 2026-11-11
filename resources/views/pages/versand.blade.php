@extends('layouts.omniva')

@section('title', 'Versand & Lieferung')
@section('meta_description', 'Kostenlose Lieferung nach Deutschland in 2–5 Werktagen. Versandpolitik der F & B Forst und Brennholz GmbH.')

@section('content')
    @php
        $nap = company_nap();
        $ship = config('merchant.shipping');
        $min = (int) ($ship['min_days'] ?? 2);
        $max = (int) ($ship['max_days'] ?? 5);
    @endphp
    @include('partials.page-title', ['pageTitle' => 'Versand & Lieferung', 'titleImageSlug' => 'banner-wide-1'])
    <section class="flat-spacing-9">
        <div class="container" style="max-width:820px;">
            <p class="text-caption mb-4">Diese Versandbedingungen gelten für alle Bestellungen im Online-Shop und stimmen mit den Angaben im Google Merchant Center überein.</p>

            <h5>Liefergebiet</h5>
            <p>Wir liefern <strong>ausschliesslich nach Deutschland</strong> ({{ $ship['zone_label'] ?? 'gesamtes Deutschland' }}). Eine Lieferung in andere Länder ist derzeit nicht möglich. Lieferadressen müssen in Deutschland liegen.</p>

            <h5>Versandkosten</h5>
            <p>
                Die Lieferung ist für <strong>alle Bestellungen nach Deutschland kostenlos</strong>
                @if ((float) ($ship['min_order_value'] ?? 0) <= 0)
                    – <strong>ohne Mindestbestellwert</strong>.
                @else
                    ab einem Bestellwert von {{ shop_money((float) $ship['min_order_value']) }}.
                @endif
                Im Warenkorb und an der Kasse wird der Versand mit <strong>{{ shop_money(0) }}</strong> ausgewiesen.
            </p>

            <h5>Lieferzeit</h5>
            <p>
                Die gesamte Lieferzeit beträgt in der Regel <strong>{{ $min }} bis {{ $max }} Werktage</strong>
                nach Bestelleingang (bzw. nach Zahlungseingang bei Vorkasse).
                Bearbeitung (Handling): {{ (int) ($ship['handling_min'] ?? 1) }}–{{ (int) ($ship['handling_max'] ?? 1) }} Werktag(e).
                Transit: {{ (int) ($ship['transit_min'] ?? 1) }}–{{ (int) ($ship['transit_max'] ?? 3) }} Werktag(e).
            </p>
            <p>Bei sperrigen oder schweren Sendungen (z.&nbsp;B. Palettenware) kann die Zustellung per Spedition erfolgen. Wir kontaktieren Sie bei Bedarf vor der Anlieferung.</p>

            <h5>Versanddienst</h5>
            <p>Service: <strong>{{ $ship['service'] ?? 'Standardversand' }}</strong>. Die Ware wird transportgerecht verpackt und versichert versandt.</p>

            <h5>Nichtannahme / erneute Zustellung</h5>
            <p>Kann eine Sendung aus von der Kundschaft zu vertretenden Gründen nicht zugestellt werden, behalten wir uns vor, die Mehrkosten einer erneuten Zustellung in Rechnung zu stellen.</p>

            <h5>Kontakt Versand</h5>
            <p>
                {{ $nap['legal_name'] ?? 'F & B Forst und Brennholz GmbH' }} ·
                <a href="mailto:{{ $nap['email'] }}">{{ $nap['email'] }}</a> ·
                <a href="tel:{{ $nap['telephone'] }}">{{ $nap['telephone_display'] }}</a>
            </p>
        </div>
    </section>
@endsection
