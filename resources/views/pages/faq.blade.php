@extends('layouts.omniva')

@section('title', 'FAQ')
@section('meta_description', 'Häufige Fragen zu Lieferung, Zahlung, Rückgabe und Produkten der Heri Brennholz GmbH.')

@section('content')
    @php
        $nap = company_nap();
        $min = (int) config('merchant.shipping.min_days', 1);
        $max = (int) config('merchant.shipping.max_days', 2);
        $returnDays = (int) config('merchant.returns.days', 14);
        $vat = rtrim(rtrim(number_format((float) config('shop.vat_rate', 8.1), 1, '.', ''), '0'), '.');
        $faqs = [
            [
                'Wie lange dauert die Lieferung?',
                "Wir liefern kostenlos innerhalb der Schweiz in der Regel in {$min} bis {$max} Werktagen nach Bestell- bzw. Zahlungseingang. Details: /versand",
            ],
            [
                'Ist die Lieferung wirklich kostenlos?',
                'Ja. Für alle Bestellungen mit Lieferadresse in der Schweiz betragen die Versandkosten CHF 0.00 – ohne Mindestbestellwert. Auslandslieferungen bieten wir nicht an.',
            ],
            [
                'Welche Zahlungsmittel akzeptieren Sie?',
                'Aktuell: '.payment_methods_text().'. Nach der Bestellung erhalten Sie die Bestätigung und die Zahlungs- bzw. Rechnungsdetails per E-Mail.',
            ],
            [
                'Sind die Preise inklusive Mehrwertsteuer?',
                "Ja. Alle Shop-Preise sind Bruttopreise in CHF inklusive {$vat} % Schweizer MWST.",
            ],
            [
                'Gibt es ein Rückgaberecht?',
                "In der Schweiz gibt es kein gesetzliches Online-Widerrufsrecht. Wir gewähren freiwillig {$returnDays} Tage Rückgabe für unbenutzte, wiederverkaufsfähige Ware. Siehe /rueckgabe.",
            ],
            [
                'Wer ist der Verkäufer?',
                ($nap['legal_name'] ?? 'Heri Brennholz GmbH').', '.($nap['address_line'] ?? '').'. UID '.($nap['vat_id'] ?? 'CHE-228.719.493').'. Vollständige Angaben im Impressum.',
            ],
            [
                'Wie erreiche ich den Kundendienst?',
                'Telefon '.$nap['telephone_display'].', E-Mail '.$nap['email'].', '.($nap['hours_label'] ?? 'Mo–Fr 08:00–18:00').'. Oder nutzen Sie das Kontaktformular unter /kontakt.',
            ],
            [
                'Liefern Sie auch ins Ausland?',
                'Nein. Verkauf und Versand erfolgen ausschliesslich in die Schweiz.',
            ],
        ];
    @endphp
    @include('partials.page-title', ['pageTitle' => 'Häufige Fragen', 'titleImageSlug' => 'hero-3'])
    <section class="flat-spacing-9">
        <div class="container" style="max-width:820px;">
            <p class="text-caption mb-4">
                Kurze Antworten zu den wichtigsten Punkten – abgestimmt auf Shop, AGB und Google Merchant Center.
            </p>
            <div class="accordion" id="faqAccordion">
                @foreach ($faqs as $i => $faq)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqH{{ $i }}">
                            <button class="accordion-button {{ $i === 0 ? '' : 'collapsed' }}" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#faqC{{ $i }}"
                                    aria-expanded="{{ $i === 0 ? 'true' : 'false' }}" aria-controls="faqC{{ $i }}">
                                {{ $faq[0] }}
                            </button>
                        </h2>
                        <div id="faqC{{ $i }}" class="accordion-collapse collapse {{ $i === 0 ? 'show' : '' }}"
                             data-bs-parent="#faqAccordion" aria-labelledby="faqH{{ $i }}">
                            <div class="accordion-body text-caption">{{ $faq[1] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
            <p class="mt-4 text-caption">
                Noch Fragen?
                <a href="{{ route('pages.contact') }}">Kontakt</a> ·
                <a href="{{ route('pages.versand') }}">Versand</a> ·
                <a href="{{ route('pages.rueckgabe') }}">Rückgabe</a> ·
                <a href="{{ route('pages.impressum') }}">Impressum</a>
            </p>
        </div>
    </section>
@endsection
