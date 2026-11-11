@extends('layouts.omniva')

@section('title', 'Bestellung')
@section('meta_description', 'Bestellung abschliessen bei F & B Forst und Brennholz GmbH – sichere Bestellung, Lieferung Deutschland, Preise inkl. MwSt.')

@section('content')
    @php
        $nap = company_nap();
        $vatLabel = rtrim(rtrim(number_format((float) config('shop.vat_rate', 19), 1, '.', ''), '0'), '.');
        $methods = collect(config('shop.payment_methods', []))->filter(fn ($m) => $m['enabled'] ?? false);
    @endphp
    @include('partials.page-title', ['pageTitle' => 'Bestellung abschließen'])

    <section class="flat-spacing-9">
        <div class="container">
            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="p-3 rounded-16 h-100" style="border:1px solid #e8e0d6;">
                        <strong style="color:#3cb54a;">{{ shipping_summary() }}</strong>
                        <div class="text-caption mt-1">Versandkosten {{ shop_money(0) }} · nur Deutschland</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 rounded-16 h-100" style="border:1px solid #e8e0d6;">
                        <strong>Preise inkl. {{ $vatLabel }}&nbsp;% MwSt</strong>
                        <div class="text-caption mt-1">Endpreise in {{ shop_currency() }} · {{ $nap['legal_name'] }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 rounded-16 h-100" style="border:1px solid #e8e0d6;">
                        <strong>{{ return_summary() }}</strong>
                        <div class="text-caption mt-1"><a href="{{ route('pages.rueckgabe') }}">Rückgaberichtlinie</a></div>
                    </div>
                </div>
            </div>

            <form action="{{ route('checkout.store') }}" method="POST" class="row" novalidate>
                @csrf
                <div class="col-lg-7 mb-4">
                    <h5 class="mb-3">Lieferadresse (Deutschland)</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-caption" for="first_name">Vorname *</label>
                            <input id="first_name" class="form-control" name="first_name" value="{{ old('first_name') }}" required maxlength="80" autocomplete="given-name">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-caption" for="last_name">Nachname *</label>
                            <input id="last_name" class="form-control" name="last_name" value="{{ old('last_name') }}" required maxlength="80" autocomplete="family-name">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-caption" for="email">E-Mail *</label>
                            <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required maxlength="120" autocomplete="email">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-caption" for="phone">Telefon *</label>
                            <input id="phone" class="form-control" type="tel" name="phone" value="{{ old('phone') }}" required maxlength="40" autocomplete="tel" placeholder="+49 …">
                        </div>
                        <div class="col-12">
                            <label class="form-label text-caption" for="company">Firma (optional)</label>
                            <input id="company" class="form-control" name="company" value="{{ old('company') }}" maxlength="120" autocomplete="organization">
                        </div>
                        <div class="col-12">
                            <label class="form-label text-caption" for="address">Strasse und Nr. *</label>
                            <input id="address" class="form-control" name="address" value="{{ old('address') }}" required maxlength="180" autocomplete="street-address">
                        </div>
                        <div class="col-12">
                            <label class="form-label text-caption" for="address_2">Adresszusatz (optional)</label>
                            <input id="address_2" class="form-control" name="address_2" value="{{ old('address_2') }}" maxlength="180">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-caption" for="postcode">PLZ *</label>
                            <input id="postcode" class="form-control" name="postcode" value="{{ old('postcode') }}" required maxlength="12" autocomplete="postal-code">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-caption" for="city">Ort *</label>
                            <input id="city" class="form-control" name="city" value="{{ old('city') }}" required maxlength="80" autocomplete="address-level2">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-caption" for="country_display">Land</label>
                            <input id="country_display" class="form-control" value="Deutschland" disabled aria-label="Land">
                            <input type="hidden" name="country" value="DE">
                        </div>
                        <div class="col-12">
                            <label class="form-label text-caption" for="notes">Anmerkungen (optional)</label>
                            <textarea id="notes" class="form-control" name="notes" rows="3" maxlength="2000">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <h5 class="mt-4 mb-3">Zahlungsart</h5>
                    <p class="text-caption mb-3">
                        Nach der Bestellung erhalten Sie die Bestätigung sowie die Zahlungsdetails bzw. die Rechnung per E-Mail.
                        Verkäufer: <strong>{{ $nap['legal_name'] }}</strong>
                        (<a href="mailto:{{ $nap['email'] }}">{{ $nap['email'] }}</a>,
                        <a href="tel:{{ $nap['telephone'] }}">{{ $nap['telephone_display'] }}</a>.
                    </p>
                    <div class="d-grid gap-2">
                        @foreach ($methods as $key => $method)
                            <label class="d-flex align-items-start gap-2 p-3 rounded-16" style="border:1px solid #e8e0d6;cursor:pointer;">
                                <input type="radio" name="payment_method" value="{{ $key }}"
                                       @checked(old('payment_method', $methods->keys()->first()) === $key) required>
                                <span>
                                    <strong class="d-block">{{ $method['label'] }}</strong>
                                    @if (! empty($method['hint']))
                                        <span class="text-caption">{{ $method['hint'] }}</span>
                                    @endif
                                </span>
                            </label>
                        @endforeach
                    </div>

                    <p class="text-caption mt-3">
                        Mit Absenden akzeptieren Sie die
                        <a href="{{ route('pages.terms') }}" target="_blank" rel="noopener">AGB</a>,
                        <a href="{{ route('pages.versand') }}" target="_blank" rel="noopener">Versandbedingungen</a>
                        und
                        <a href="{{ route('pages.rueckgabe') }}" target="_blank" rel="noopener">Rückgaberichtlinie</a>
                        sowie die
                        <a href="{{ route('pages.privacy') }}" target="_blank" rel="noopener">Datenschutzerklärung</a>.
                    </p>
                </div>

                <div class="col-lg-5">
                    <div class="p-4 rounded-16" style="background:#f7f3ee;">
                        <h5 class="mb-3">Zusammenfassung</h5>
                        @foreach ($items as $item)
                            <div class="d-flex justify-content-between mb-2 text-caption">
                                <span>{{ $item['product']->name }} × {{ $item['quantity'] }}</span>
                                <span>{{ shop_money($item['line_total']) }}</span>
                            </div>
                        @endforeach
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Zwischensumme</span>
                            <span>{{ shop_money($cart->subtotal()) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Versand (DE, kostenlos)</span>
                            <span>{{ shop_money($cart->shipping()) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 text-caption">
                            <span>inkl. MwSt ({{ $vatLabel }}&nbsp;%)</span>
                            <span>{{ shop_money(vat_included($cart->total())) }}</span>
                        </div>
                        <div class="d-flex justify-content-between fw-bold fs-5 mb-3">
                            <span>Gesamt</span>
                            <span>{{ shop_money($cart->total()) }}</span>
                        </div>
                        <button type="submit" class="tf-btn animate-btn w-100 justify-content-center">
                            Kostenpflichtig bestellen
                        </button>
                        <p class="text-caption mt-3 mb-0">
                            Verbindung via HTTPS ·
                            <a href="{{ route('pages.impressum') }}">Impressum</a> ·
                            <a href="{{ route('pages.contact') }}">Kontakt</a>
                        </p>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
