@extends('layouts.omniva')

@section('title', 'Impressum')
@section('meta_description', 'Impressum und Anbieterkennzeichnung der F & B Forst und Brennholz GmbH, Hohenfels (Deutschland).')

@section('content')
    @php $nap = company_nap(); @endphp
    @include('partials.page-title', ['pageTitle' => 'Impressum', 'titleImageSlug' => 'banner-wide-1'])
    <section class="flat-spacing-9">
        <div class="container" style="max-width:820px;">
            <p class="text-caption mb-4">Anbieterkennzeichnung gemäss § 5 TMG / § 18 MStV und Google-Händleranforderungen.</p>

            <h5>Anbieterkennzeichnung</h5>
            <p>
                {{ $nap['legal_name'] }}<br>
                <strong>Sitz:</strong><br>
                {{ $nap['street'] }}<br>
                {{ $nap['postal_code'] }} {{ $nap['locality'] ?? $nap['city'] }}<br>
                {{ $nap['country_name'] }}
            </p>
            @if (! empty($nap['operating_address']['street']))
                <p>
                    <strong>{{ $nap['operating_address']['label'] ?? 'Betriebsstätte / Lager' }}:</strong><br>
                    {{ $nap['operating_address']['street'] }}<br>
                    {{ $nap['operating_address']['postal_code'] ?? '' }} {{ $nap['operating_address']['locality'] ?? '' }}<br>
                    {{ $nap['operating_address']['country_name'] ?? $nap['country_name'] }}
                </p>
            @endif

            @php
                $directors = $nap['managing_directors'] ?? [];
                if ($directors === [] && ! empty($nap['managing_director'])) {
                    $directors = [['name' => $nap['managing_director'], 'role' => 'Geschäftsführer']];
                }
            @endphp
            @if ($directors !== [])
                <h5>Vertretungsberechtigt</h5>
                <ul>
                    @foreach ($directors as $director)
                        <li>
                            {{ $director['name'] }}, {{ $director['role'] ?? 'Geschäftsführer' }}
                            @if (! empty($director['share']))
                                (Gesellschafteranteil {{ $director['share'] }})
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif

            <h5>Handelsregister &amp; Identifikation</h5>
            <p>
                Registergericht: {{ $nap['register_court'] ?? 'Amtsgericht Nürnberg' }}<br>
                Handelsregister-Nr.: {{ $nap['hr_number'] ?? 'HRB 40744' }}<br>
                @if (! empty($nap['european_id']))
                    Europäische Identifikationsnummer: {{ $nap['european_id'] }}<br>
                @endif
                @if (! empty($nap['vat_id']))
                    USt-IdNr.: {{ $nap['vat_id'] }}<br>
                @endif
                Rechtsform: {{ $nap['legal_form'] ?? 'Gesellschaft mit beschränkter Haftung (GmbH)' }}<br>
                Stammkapital: {{ $nap['share_capital'] ?? '25.100 €' }}<br>
                Sitz: {{ $nap['locality'] ?? $nap['city'] }}, {{ $nap['region'] ?? 'Bayern' }}, {{ $nap['country_name'] ?? 'Deutschland' }}
            </p>

            <h5>Kontakt</h5>
            <p>
                E-Mail: <a href="mailto:{{ $nap['email'] }}">{{ $nap['email'] }}</a><br>
                Telefon: <a href="tel:{{ $nap['telephone'] }}">{{ $nap['telephone_display'] }}</a><br>
                Website: <a href="{{ $nap['website'] ?? url('/') }}" rel="noopener">{{ parse_url($nap['website'] ?? url('/'), PHP_URL_HOST) ?: request()->getHost() }}</a><br>
                Erreichbarkeit: {{ $nap['hours_label'] ?? 'Montag–Freitag, 08:00–18:00 Uhr' }}
            </p>

            <h5>Geschäftstätigkeit</h5>
            <p>{{ config('merchant.business_model.description', 'Handel mit Brennholz und Holz sowie Online-Shop für Brennholz, Holzpellets, Holzbriketts und Zubehör. Verkauf und Lieferung in Deutschland.') }}</p>
            @if (! empty(config('merchant.business_model.activities')))
                <ul>
                    @foreach (config('merchant.business_model.activities') as $activity)
                        <li>{{ $activity }}</li>
                    @endforeach
                </ul>
            @endif

            <h5>Haftungsausschluss</h5>
            <p>Trotz sorgfältiger inhaltlicher Kontrolle übernehmen wir keine Haftung für die Inhalte externer Links. Für den Inhalt der verlinkten Seiten sind ausschliesslich deren Betreiber verantwortlich.</p>

            <h5>Streitbeilegung</h5>
            <p>
                Die Europäische Kommission stellt eine Plattform zur Online-Streitbeilegung (OS) bereit:
                <a href="https://ec.europa.eu/consumers/odr/" target="_blank" rel="noopener">https://ec.europa.eu/consumers/odr/</a>.
                Wir sind nicht verpflichtet und nicht bereit, an Streitbeilegungsverfahren vor einer Verbraucherschlichtungsstelle teilzunehmen.
            </p>

            <p class="text-caption mt-4">
                Weitere Informationen:
                <a href="{{ route('pages.terms') }}">AGB</a> ·
                <a href="{{ route('pages.privacy') }}">Datenschutz</a> ·
                <a href="{{ route('pages.versand') }}">Versand</a> ·
                <a href="{{ route('pages.rueckgabe') }}">Rückgabe</a> ·
                <a href="{{ route('pages.contact') }}">Kontakt</a>
            </p>
        </div>
    </section>
@endsection
