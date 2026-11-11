@extends('layouts.omniva')

@section('title', 'FAQ')
@section('meta_description', 'Häufige Fragen zu Lieferung, Zahlung, Rückgabe und Produkten der F & B Forst und Brennholz GmbH.')

@section('content')
    @php
        $nap = company_nap();
        $min = (int) config('merchant.shipping.min_days', 2);
        $max = (int) config('merchant.shipping.max_days', 5);
        $returnDays = (int) config('merchant.returns.days', 14);
        $vat = rtrim(rtrim(number_format((float) config('shop.vat_rate', 19), 1, '.', ''), '0'), '.');
        $faqs = [
            [
                'Wie lange dauert die Lieferung?',
                "Wir liefern kostenlos innerhalb Deutschlands in der Regel in {$min} bis {$max} Werktagen nach Bestell- bzw. Zahlungseingang. Details: /versand",
            ],
            [
                'Ist die Lieferung wirklich kostenlos?',
                'Ja. Für alle Bestellungen mit Lieferadresse in Deutschland betragen die Versandkosten '.shop_money(0).' – ohne Mindestbestellwert. Lieferungen ausserhalb Deutschlands bieten wir nicht an.',
            ],
            [
                'Welche Zahlungsmittel akzeptieren Sie?',
                'Aktuell: '.payment_methods_text().'. Nach der Bestellung erhalten Sie die Bestätigung und die Zahlungs- bzw. Rechnungsdetails per E-Mail.',
            ],
            [
                'Sind die Preise inklusive Mehrwertsteuer?',
                'Ja. Alle Shop-Preise sind Bruttopreise in '.shop_currency()." inklusive {$vat} % deutscher MwSt.",
            ],
            [
                'Gibt es ein Rückgaberecht?',
                "Für Verbraucherinnen und Verbraucher gilt das gesetzliche 14-tägige Widerrufsrecht. Zusätzlich gewähren wir {$returnDays} Tage Rückgabe für unbenutzte, wiederverkaufsfähige Ware. Siehe /rueckgabe.",
            ],
            [
                'Wer ist der Verkäufer?',
                ($nap['legal_name'] ?? 'F & B Forst und Brennholz GmbH').', '.($nap['address_line'] ?? '').'. '.$nap['hr_number'].', '.$nap['register_court'].'. Vollständige Angaben im Impressum.',
            ],
            [
                'Wie erreiche ich den Kundendienst?',
                'Telefon '.$nap['telephone_display'].', E-Mail '.$nap['email'].', '.($nap['hours_label'] ?? 'Mo–Fr 08:00–18:00').'. Oder nutzen Sie das Kontaktformular unter /kontakt.',
            ],
            [
                'Liefern Sie auch ins Ausland?',
                'Nein. Verkauf und Versand erfolgen ausschliesslich nach Deutschland.',
            ],
        ];
    @endphp
    @include('partials.page-title', ['pageTitle' => 'Häufige Fragen (FAQ)', 'titleImageSlug' => 'banner-wide-1'])
    <section class="flat-spacing-9">
        <div class="container" style="max-width:820px;">
            <div class="accordion" id="faqAccordion">
                @foreach ($faqs as $i => $pair)
                    <div class="accordion-item border-0 mb-2" style="background:#f7f7f7;border-radius:8px;">
                        <h2 class="accordion-header" id="faqH{{ $i }}">
                            <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faqC{{ $i }}" aria-expanded="false" aria-controls="faqC{{ $i }}" style="background:transparent;box-shadow:none;">
                                {{ $pair[0] }}
                            </button>
                        </h2>
                        <div id="faqC{{ $i }}" class="accordion-collapse collapse" aria-labelledby="faqH{{ $i }}" data-bs-parent="#faqAccordion">
                            <div class="accordion-body pt-0">{{ $pair[1] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
