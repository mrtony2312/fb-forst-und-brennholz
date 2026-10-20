@extends('layouts.omniva')

@section('title', 'Impressum')
@section('meta_description', 'Impressum und Anbieterkennzeichnung der Heri Brennholz GmbH, Biberist (Schweiz).')

@section('content')
    @php $nap = company_nap(); @endphp
    @include('partials.page-title', ['pageTitle' => 'Impressum', 'titleImageSlug' => 'banner-wide-1'])
    <section class="flat-spacing-9">
        <div class="container" style="max-width:820px;">
            <p class="text-caption mb-4">Transparente Anbieterkennzeichnung gemäss Schweizer Recht und Google-Händleranforderungen.</p>

            <h5>Anbieterkennzeichnung</h5>
            <p>
                {{ $nap['legal_name'] }}<br>
                {{ $nap['street'] }}<br>
                {{ $nap['postal_code'] }} {{ $nap['locality'] ?? $nap['city'] }}<br>
                {{ $nap['country_name'] }}
            </p>

            <h5>Vertretungsberechtigt</h5>
            <p>{{ $nap['managing_director'] ?? 'Markus Heri' }}, Geschäftsführer (Einzelunterschrift)</p>

            <h5>Handelsregister &amp; Identifikation</h5>
            <p>
                Handelsregister-Nr.: {{ $nap['hr_number'] ?? $nap['hr'] ?? 'CH-241.4.020.905-9' }}<br>
                UID / MWST-Nr.: {{ $nap['vat_id'] ?? $nap['uid'] ?? 'CHE-228.719.493' }}<br>
                Rechtsform: {{ $nap['legal_form'] ?? 'Gesellschaft mit beschränkter Haftung (GmbH)' }}<br>
                Sitz: {{ $nap['locality'] ?? $nap['city'] }}, Kanton {{ $nap['region'] ?? 'Solothurn' }}
            </p>

            <h5>Kontakt</h5>
            <p>
                E-Mail: <a href="mailto:{{ $nap['email'] }}">{{ $nap['email'] }}</a><br>
                Telefon: <a href="tel:{{ $nap['telephone'] }}">{{ $nap['telephone_display'] }}</a><br>
                Website: <a href="{{ $nap['website'] ?? url('/') }}" rel="noopener">{{ parse_url($nap['website'] ?? url('/'), PHP_URL_HOST) ?: 'heribrennholzgmbh.com' }}</a><br>
                Erreichbarkeit: {{ $nap['hours_label'] ?? 'Montag–Freitag, 08:00–18:00 Uhr' }}
            </p>

            <h5>Geschäftstätigkeit</h5>
            <p>{{ config('merchant.business_model.description', 'Online-Shop für Brennholz, Holzpellets, Holzbriketts und Zubehör. Verkauf und Lieferung ausschliesslich in der Schweiz.') }}</p>

            <h5>Haftungsausschluss</h5>
            <p>Trotz sorgfältiger inhaltlicher Kontrolle übernehmen wir keine Haftung für die Inhalte externer Links. Für den Inhalt der verlinkten Seiten sind ausschliesslich deren Betreiber verantwortlich.</p>

            <h5>Streitbeilegung</h5>
            <p>Wir sind nicht verpflichtet und nicht bereit, an Streitbeilegungsverfahren vor einer Verbraucherschlichtungsstelle teilzunehmen.</p>

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
