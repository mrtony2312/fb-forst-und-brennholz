@extends('layouts.omniva')

@section('title', 'Versand & Lieferung')
@section('meta_description', 'Kostenlose Lieferung in der ganzen Schweiz in 1–2 Werktagen. Versandpolitik der Heri Brennholz GmbH.')

@section('content')
    @php
        $nap = company_nap();
        $ship = config('merchant.shipping');
        $min = (int) ($ship['min_days'] ?? 1);
        $max = (int) ($ship['max_days'] ?? 2);
    @endphp
    @include('partials.page-title', ['pageTitle' => 'Versand & Lieferung', 'titleImageSlug' => 'banner-wide-1'])
    <section class="flat-spacing-9">
        <div class="container" style="max-width:820px;">
            <p class="text-caption mb-4">Diese Versandbedingungen gelten für alle Bestellungen im Online-Shop und stimmen mit den Angaben im Google Merchant Center überein.</p>

            <h5>Liefergebiet</h5>
            <p>Wir liefern <strong>ausschliesslich innerhalb der Schweiz</strong> ({{ $ship['zone_label'] ?? 'gesamte Schweiz' }}). Eine Lieferung ins Ausland ist derzeit nicht möglich. Lieferadressen müssen in der Schweiz liegen.</p>

            <h5>Versandkosten</h5>
            <p>
                Die Lieferung ist für <strong>alle Bestellungen in der Schweiz kostenlos</strong>
                @if ((float) ($ship['min_order_value'] ?? 0) <= 0)
                    – <strong>ohne Mindestbestellwert</strong>.
                @else
                    ab einem Bestellwert von CHF {{ number_format((float) $ship['min_order_value'], 2, '.', "'") }}.
                @endif
                Im Warenkorb und an der Kasse wird der Versand mit <strong>CHF 0.00</strong> ausgewiesen.
            </p>

            <h5>Lieferzeit</h5>
            <p>
                Die gesamte Lieferzeit beträgt in der Regel <strong>{{ $min }} bis {{ $max }} Werktage</strong>
                nach Bestelleingang (bzw. nach Zahlungseingang bei Vorkasse).
                Bearbeitung (Handling): {{ (int) ($ship['handling_min'] ?? 1) }}–{{ (int) ($ship['handling_max'] ?? 1) }} Werktag(e).
                Transit: {{ (int) ($ship['transit_min'] ?? 0) }}–{{ (int) ($ship['transit_max'] ?? 1) }} Werktag(e).
            </p>
            <p>Bei sperrigen oder schweren Sendungen (z.&nbsp;B. Palettenware) kann die Zustellung per Spedition erfolgen. Wir kontaktieren Sie bei Bedarf vor der Anlieferung.</p>

            <h5>Versanddienst</h5>
            <p>Service: <strong>{{ $ship['service'] ?? 'Standardversand' }}</strong>. Die Ware wird transportgerecht verpackt und versichert versandt.</p>

            <h5>Nichtannahme / erneute Zustellung</h5>
            <p>Kann eine Sendung aus von der Kundschaft zu vertretenden Gründen nicht zugestellt werden, behalten wir uns vor, die Mehrkosten einer erneuten Zustellung in Rechnung zu stellen.</p>

            <h5>Fragen zur Lieferung</h5>
            <p>
                Bei Fragen erreichen Sie uns über die
                <a href="{{ route('pages.contact') }}">Kontaktseite</a>
                oder telefonisch unter
                <a href="tel:{{ $nap['telephone'] }}">{{ $nap['telephone_display'] }}</a>
                ({{ $nap['hours_label'] ?? 'Mo–Fr 08:00–18:00' }}).
            </p>

            <p class="text-caption mt-4">
                Siehe auch:
                <a href="{{ route('pages.rueckgabe') }}">Rückgaberecht</a> ·
                <a href="{{ route('pages.terms') }}">AGB</a>
            </p>
        </div>
    </section>
@endsection
