@extends('layouts.omniva')

@section('title', 'Bestellung bestätigt')
@section('meta_description', 'Ihre Bestellung bei F & B Forst und Brennholz GmbH wurde erfolgreich aufgenommen.')

@section('content')
    @php $nap = company_nap(); @endphp
    @include('partials.page-title', ['pageTitle' => 'Vielen Dank für Ihre Bestellung'])

    <section class="flat-spacing-9">
        <div class="container" style="max-width:720px;">
            <div class="text-center mb-4">
                <div class="h4">Bestellung <strong>{{ $order->reference }}</strong> eingegangen</div>
                <p class="opacity-75">Eine Bestätigung wurde an {{ $order->email }} gesendet.</p>
                <p class="text-caption">
                    Verkäufer: {{ $nap['legal_name'] }} ·
                    <a href="mailto:{{ $nap['email'] }}">{{ $nap['email'] }}</a> ·
                    <a href="tel:{{ $nap['telephone'] }}">{{ $nap['telephone_display'] }}</a>
                </p>
            </div>

            <div style="border:1px solid #ece7e1;border-radius:12px;padding:24px;">
                <table class="table">
                    <tbody>
                        @foreach ($order->items as $item)
                            <tr>
                                <td>{{ $item->name }} × {{ $item->quantity }}</td>
                                <td class="text-end">{{ shop_money((float) $item->line_total) }}</td>
                            </tr>
                        @endforeach
                        <tr><td>Zwischensumme</td><td class="text-end">{{ shop_money((float) $order->subtotal) }}</td></tr>
                        <tr><td>Versand (DE)</td><td class="text-end">{{ ((float) $order->shipping) > 0 ? shop_money((float) $order->shipping) : 'Kostenlos ('.shop_money(0).')' }}</td></tr>
                        <tr class="fw-semibold"><td>Gesamtbetrag inkl. MwSt</td><td class="text-end">{{ shop_money((float) $order->total) }}</td></tr>
                        <tr class="text-caption"><td>Darin enthaltene MwSt ({{ rtrim(rtrim(number_format(config('shop.vat_rate'), 1), '0'), '.') }} %)</td><td class="text-end">{{ shop_money(vat_included($order->total)) }}</td></tr>
                        @if ($order->payment_method)
                            <tr class="text-caption"><td>Zahlungsart</td><td class="text-end">{{ config('shop.payment_methods.'.$order->payment_method.'.label', $order->payment_method) }}</td></tr>
                        @endif
                    </tbody>
                </table>
                <p class="mb-1">
                    <strong>Lieferadresse:</strong>
                    {{ $order->first_name }} {{ $order->last_name }}@if($order->company), {{ $order->company }}@endif,
                    {{ $order->address }}@if($order->address_2), {{ $order->address_2 }}@endif,
                    {{ $order->postcode }} {{ $order->city }},
                    {{ $order->country === 'DE' ? 'Deutschland' : $order->country }}
                </p>
                <p class="text-caption mb-0">
                    Lieferzeit: {{ shipping_summary() }}.
                    Rückgabe: <a href="{{ route('pages.rueckgabe') }}">Richtlinie</a>.
                </p>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('shop.index') }}" class="tf-btn btn-fill animate-btn"><span>Zurück zum Shop</span></a>
                <a href="{{ route('pages.contact') }}" class="tf-btn-line ms-3">Fragen zur Bestellung?</a>
            </div>
        </div>
    </section>
@endsection
