@extends('layouts.omniva')

@section('title', 'Über uns')
@section('meta_description', 'F & B Forst und Brennholz GmbH in Hohenfels: Brennholz, Pellets und Briketts – transparent, mit kostenloser Lieferung in Deutschland.')

@php
    $img = fn ($n, $fallback = 'assets/images/item/item-bg.jpg') =>
        is_file(public_path("media/site/{$n}.jpg")) ? "/media/site/{$n}.jpg" : '/'.ltrim($fallback, '/');
    $nap = company_nap();
@endphp

@section('content')
    @include('partials.page-title', ['pageTitle' => 'Über F & B Forst und Brennholz', 'titleImageSlug' => 'about-1'])
    <section class="flat-spacing-9">
        <div class="container">
            <div class="row align-items-center g-4 mb-5">
                <div class="col-lg-6">
                    <img src="{{ $img('about-1') }}" alt="Brennholzlager {{ $nap['legal_name'] }}" class="rounded-16 w-100" style="max-height:420px;object-fit:cover;">
                </div>
                <div class="col-lg-6">
                    <h4 class="mb-3">Qualität direkt vom Anbieter</h4>
                    <p>
                        Die <strong>{{ $nap['legal_name'] }}</strong> mit Sitz in
                        {{ $nap['locality'] ?? $nap['city'] }} ({{ $nap['region'] ?? 'Bayern' }}, {{ $nap['country_name'] ?? 'Deutschland' }})
                        vertreibt Brennholz, Kaminholz, Holzpellets, Holzbriketts und passendes Zubehör
                        über unseren Online-Shop.
                    </p>
                    <p>
                        Wir sind eine eingetragene deutsche GmbH
                        ({{ $nap['register_court'] ?? 'Amtsgericht Nürnberg' }},
                        {{ $nap['hr_number'] ?? 'HRB 40744' }}).
                        @php $directors = $nap['managing_directors'] ?? []; @endphp
                        @if ($directors !== [])
                            Geschäftsführer:
                            {{ collect($directors)->pluck('name')->filter()->join(', ') }}.
                        @elseif (! empty($nap['managing_director']))
                            Geschäftsführer: {{ $nap['managing_director'] }}.
                        @endif
                    </p>
                    <p>
                        Unser Modell ist klar und transparent: Sie bestellen online, wir bestätigen per E-Mail
                        und liefern <strong>kostenlos nach Deutschland</strong>
                        (in der Regel {{ (int) config('merchant.shipping.min_days', 2) }}–{{ (int) config('merchant.shipping.max_days', 5) }} Werktage).
                        Zahlung derzeit über {{ payment_methods_text() }}.
                    </p>
                </div>
            </div>

            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div class="p-4 rounded-16 h-100" style="background:#f7f3ee;">
                        <h6>Identität</h6>
                        <p class="text-caption mb-0">
                            {{ $nap['legal_name'] }}<br>
                            {{ $nap['street'] }}<br>
                            {{ $nap['postal_code'] }} {{ $nap['locality'] ?? $nap['city'] }}<br>
                            <a href="{{ route('pages.impressum') }}">Vollständiges Impressum</a>
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 rounded-16 h-100" style="background:#f7f3ee;">
                        <h6>Erreichbarkeit</h6>
                        <p class="text-caption mb-0">
                            {{ $nap['hours_label'] ?? 'Mo–Fr 08:00–18:00' }}<br>
                            <a href="tel:{{ $nap['telephone'] }}">{{ $nap['telephone_display'] }}</a><br>
                            <a href="mailto:{{ $nap['email'] }}">{{ $nap['email'] }}</a>
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 rounded-16 h-100" style="background:#f7f3ee;">
                        <h6>Kundenservice</h6>
                        <p class="text-caption mb-0">
                            {{ shipping_summary() }}.<br>
                            {{ return_summary() }}.<br>
                            <a href="{{ route('pages.contact') }}">Kontakt aufnehmen</a>
                        </p>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <a href="{{ route('shop.index') }}" class="tf-btn animate-btn">Zum Shop</a>
                <a href="{{ route('pages.contact') }}" class="tf-btn-line ms-3">Fragen? Schreiben Sie uns</a>
            </div>
        </div>
    </section>
@endsection
