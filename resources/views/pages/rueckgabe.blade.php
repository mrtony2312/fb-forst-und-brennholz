@extends('layouts.omniva')

@section('title', 'Rückgabe & Widerruf')
@section('meta_description', '14-tägiges Widerrufsrecht und Rückgabebedingungen der F & B Forst und Brennholz GmbH für Bestellungen nach Deutschland.')

@section('content')
    @php
        $nap = company_nap();
        $ret = config('merchant.returns');
        $days = (int) ($ret['days'] ?? 14);
        $refundDays = (int) ($ret['refund_days'] ?? 14);
        $returnCost = (float) ($ret['return_shipping_cost'] ?? 19.90);
        $customerPays = (bool) ($ret['customer_pays_return_shipping'] ?? true);
    @endphp
    @include('partials.page-title', ['pageTitle' => 'Rückgabe & Widerruf', 'titleImageSlug' => 'banner-wide-1'])
    <section class="flat-spacing-9">
        <div class="container" style="max-width:820px;">
            <p class="text-caption mb-4">Für Verbraucherinnen und Verbraucher gilt bei Fernabsatzverträgen das gesetzliche <strong>14-tägige Widerrufsrecht</strong> (EU/DE). Nachfolgend die wichtigsten Punkte unserer Rückgaberichtlinie.</p>

            <h5>Widerrufsrecht</h5>
            <p>
                Sie können die Bestellung innerhalb von <strong>{{ $days }} Tagen</strong> nach Erhalt der Ware ohne Angabe von Gründen widerrufen,
                sofern die Ware unbenutzt, unbeschädigt und in wiederverkaufsfähigem Zustand ist (Originalverpackung soweit zumutbar).
            </p>

            <h5>Ausnahmen</h5>
            <ul>
                <li>Angebrochene oder verbrauchte Brennstoffe (z.&nbsp;B. geöffnete Pelletsäcke, verfeuertes Holz)</li>
                <li>Ware, die nach Kundenspezifikation angefertigt oder klar personalisiert wurde</li>
                <li>Beschädigte oder stark verschmutzte Artikel durch unsachgemässe Handhabung</li>
            </ul>

            <h5>Rückgabeprozess</h5>
            <ol>
                <li>Kontaktieren Sie uns vor der Rücksendung per E-Mail an
                    <a href="mailto:{{ $nap['email'] }}">{{ $nap['email'] }}</a>
                    oder telefonisch unter
                    <a href="tel:{{ $nap['telephone'] }}">{{ $nap['telephone_display'] }}</a>
                    und nennen Sie Ihre Bestellnummer.</li>
                <li>Sie erhalten von uns die Rücksendeadresse und eine Bestätigung.</li>
                <li>Senden Sie die Ware vollständig und gut verpackt an die mitgeteilte Adresse in Deutschland.</li>
            </ol>

            <h5>Rücksendekosten</h5>
            <p>
                @if ($customerPays)
                    Die Kosten der Rücksendung trägt die Kundin bzw. der Kunde
                    (Richtwert ca. <strong>{{ shop_money($returnCost) }}</strong>, abhängig von Gewicht und Spedition).
                    Ausgenommen sind Fälle, in denen wir eine falsche oder mangelhafte Ware geliefert haben – dann übernehmen wir die Rücksendekosten.
                @else
                    Wir übernehmen die Kosten der Rücksendung.
                @endif
            </p>

            <h5>Erstattung</h5>
            <p>
                Nach Eingang und Prüfung der Ware erstatten wir den Kaufpreis innerhalb von
                <strong>{{ $refundDays }} Tagen</strong> auf dem ursprünglichen Zahlungsweg
                (abzüglich allfälliger von der Kundschaft zu tragender Rücksendekosten, sofern vereinbart).
            </p>

            <h5>Mängel / Garantie</h5>
            <p>
                Bei Mängeln gelten die gesetzlichen Gewährleistungsrechte.
                Bitte melden Sie offensichtliche Transportschäden umgehend nach Erhalt mit Fotos an
                <a href="mailto:{{ $nap['email'] }}">{{ $nap['email'] }}</a>.
            </p>

            <p class="text-caption mt-4">
                Rücksendeadresse und Hilfe:
                <a href="{{ route('pages.contact') }}">Kontaktseite</a> ·
                <a href="{{ route('pages.terms') }}">AGB</a> ·
                <a href="{{ route('pages.impressum') }}">Impressum</a>
            </p>
        </div>
    </section>
@endsection
