@extends('layouts.omniva')

@section('title', 'Allgemeine Geschäftsbedingungen')
@section('meta_description', 'AGB der F & B Forst und Brennholz GmbH: Preise inkl. MwSt, Lieferung DE, Zahlung, Widerruf und Haftung.')

@section('content')
    @php
        $nap = company_nap();
        $vat = rtrim(rtrim(number_format((float) config('shop.vat_rate', 19), 1, '.', ''), '0'), '.');
        $min = (int) config('merchant.shipping.min_days', 2);
        $max = (int) config('merchant.shipping.max_days', 5);
        $returnDays = (int) config('merchant.returns.days', 14);
    @endphp
    @include('partials.page-title', ['pageTitle' => 'Allgemeine Geschäftsbedingungen', 'titleImageSlug' => 'banner-wide-1'])
    <section class="flat-spacing-9">
        <div class="container" style="max-width:820px;">
            <p class="text-caption">Stand: {{ now()->format('d.m.Y') }}</p>

            <h5>1. Geltungsbereich und Anbieter</h5>
            <p>
                Diese Allgemeinen Geschäftsbedingungen (AGB) regeln den Verkauf von Holzbrennstoffen
                (Brennholz, Kaminholz, Holzpellets, Holzbriketts) und Zubehör über den Online-Shop
                <strong>{{ parse_url($nap['website'] ?? url('/'), PHP_URL_HOST) ?: 'heribrennholzgmbh.com' }}</strong>
                der <strong>{{ $nap['legal_name'] }}</strong>,
                {{ $nap['address_line'] ?? ($nap['street'].', '.$nap['postal_code'].' '.($nap['locality'] ?? $nap['city']).', '.$nap['country_name']) }}
                ({{ $nap['register_court'] ?? 'Amtsgericht Nürnberg' }}, {{ $nap['hr_number'] ?? 'HRB 40744' }}@if (! empty($nap['vat_id'])), USt-IdNr. {{ $nap['vat_id'] }}@endif).
            </p>
            <p>
                Vertragspartner ist ausschliesslich die {{ $nap['legal_name'] }}.
                Der Shop richtet sich an Endverbraucher und Gewerbekunden in <strong>Deutschland</strong>.
            </p>

            <h5>2. Vertragsabschluss</h5>
            <p>
                Die Darstellung der Produkte im Online-Shop stellt kein rechtlich bindendes Angebot dar,
                sondern eine Aufforderung zur Bestellung. Mit Absenden der Bestellung geben Sie ein
                verbindliches Angebot ab. Der Vertrag kommt zustande, wenn wir die Bestellung per E-Mail
                bestätigen oder die Ware versenden.
            </p>

            <h5>3. Preise und Zahlung</h5>
            <p>
                Alle Preise verstehen sich in <strong>{{ shop_currency() }}</strong> als <strong>Endpreise inklusive</strong>
                der gesetzlichen deutschen Mehrwertsteuer (MwSt, Normalsatz {{ $vat }}&nbsp;%).
                Allfällige Rabatte oder Aktionspreise sind auf der Produktseite klar ausgewiesen.
            </p>
            <p>
                Verfügbare Zahlungsarten: <strong>{{ payment_methods_text() }}</strong>.
                Bei Kauf auf Rechnung erhalten Sie die Rechnung per E-Mail (zahlbar innert 10 Tagen).
                Bei Vorkasse erfolgt der Versand nach Zahlungseingang.
            </p>

            <h5>4. Lieferung</h5>
            <p>
                Lieferung ausschliesslich nach <strong>Deutschland</strong>.
                Details siehe <a href="{{ route('pages.versand') }}">Versandseite</a>.
            </p>
            <ul>
                <li><strong>Lieferzeit:</strong> {{ $min }} bis {{ $max }} Werktage (nach Bestell- bzw. Zahlungseingang).</li>
                <li><strong>Versandkosten:</strong> Die Lieferung ist für alle Bestellungen nach Deutschland
                    <strong>kostenlos</strong>, unabhängig vom Bestellwert (Versand {{ shop_money(0) }}).</li>
                <li>Gefahrübergang mit Übergabe an die transportierende Person bzw. bei Annahmeverzug der Kundschaft.</li>
            </ul>

            <h5>5. Widerruf / Rückgabe</h5>
            <p>
                Für Verbraucherinnen und Verbraucher gilt das gesetzliche 14-tägige Widerrufsrecht.
                Details und Ausnahmen finden Sie in unserer
                <a href="{{ route('pages.rueckgabe') }}">Rückgabe- und Widerrufsrichtlinie</a>
                (Frist: <strong>{{ $returnDays }} Tage</strong>).
            </p>

            <h5>6. Eigentumsvorbehalt</h5>
            <p>Die gelieferte Ware bleibt bis zur vollständigen Bezahlung Eigentum der {{ $nap['legal_name'] }}.</p>

            <h5>7. Mängelrechte</h5>
            <p>
                Es gelten die gesetzlichen Gewährleistungsrechte.
                Offensichtliche Transportschäden sind unverzüglich mit Fotos an
                <a href="mailto:{{ $nap['email'] }}">{{ $nap['email'] }}</a> zu melden.
            </p>

            <h5>8. Haftung</h5>
            <p>
                Wir haften unbeschränkt für Vorsatz und grobe Fahrlässigkeit sowie nach zwingendem Produkthaftpflichtrecht.
                Bei leichter Fahrlässigkeit haften wir nur für Verletzung wesentlicher Vertragspflichten und begrenzt
                auf den vorhersehbaren, vertragstypischen Schaden. Die Haftung für indirekte Schäden und entgangenen Gewinn
                ist – soweit gesetzlich zulässig – ausgeschlossen.
            </p>

            <h5>9. Datenschutz</h5>
            <p>Informationen zur Verarbeitung personenbezogener Daten finden Sie in unserer
                <a href="{{ route('pages.privacy') }}">Datenschutzerklärung</a>.</p>

            <h5>10. Schlussbestimmungen</h5>
            <p>
                Es gilt das Recht der Bundesrepublik Deutschland unter Ausschluss des UN-Kaufrechts (CISG).
                Gerichtsstand ist – soweit zulässig – der Sitz der {{ $nap['legal_name'] }}
                ({{ $nap['locality'] ?? $nap['city'] }}, Deutschland).
                Sollten einzelne Bestimmungen unwirksam sein, bleibt die Wirksamkeit der übrigen unberührt.
            </p>

            <p>
                Fragen: <a href="mailto:{{ $nap['email'] }}">{{ $nap['email'] }}</a>
                oder telefonisch unter {{ $nap['telephone_display'] }}.
            </p>
        </div>
    </section>
@endsection
