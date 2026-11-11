@extends('layouts.omniva')

@section('title', 'Datenschutzerklärung')
@section('meta_description', 'Datenschutzerklärung der F & B Forst und Brennholz GmbH: Erhebung, Nutzung und Schutz Ihrer Daten.')

@section('content')
    @php $nap = company_nap(); @endphp
    @include('partials.page-title', ['pageTitle' => 'Datenschutzerklärung', 'titleImageSlug' => 'banner-wide-1'])
    <section class="flat-spacing-9">
        <div class="container" style="max-width:820px;">
            <p class="text-caption">Stand: {{ now()->format('d.m.Y') }}</p>

            <h5>1. Verantwortliche Stelle</h5>
            <p>
                {{ $nap['legal_name'] }}<br>
                {{ $nap['street'] }}<br>
                {{ $nap['postal_code'] }} {{ $nap['locality'] ?? $nap['city'] }}, {{ $nap['country_name'] }}<br>
                E-Mail: <a href="mailto:{{ $nap['email'] }}">{{ $nap['email'] }}</a><br>
                Telefon: <a href="tel:{{ $nap['telephone'] }}">{{ $nap['telephone_display'] }}</a><br>
                {{ $nap['hr_number'] ?? 'HRB 40744' }} · {{ $nap['register_court'] ?? 'Amtsgericht Nürnberg' }}
                @if (! empty($nap['vat_id']))
                    <br>USt-IdNr.: {{ $nap['vat_id'] }}
                @endif
            </p>

            <h5>2. Erhobene Daten</h5>
            <p>Wir verarbeiten Daten, die Sie uns mitteilen oder die bei der Nutzung des Shops anfallen:</p>
            <ul>
                <li>Kontaktdaten (Name, Adresse, E-Mail, Telefon) bei Bestellung oder Kontaktformular</li>
                <li>Bestell- und Zahlungsdaten (gewählte Zahlungsart, Bestellhistorie)</li>
                <li>Technische Daten (IP-Adresse, Browser, Zeitstempel) in Server- und Sicherheitslogs</li>
                <li>Session-Daten für Warenkorb und Wunschzettel</li>
            </ul>

            <h5>3. Zwecke und Rechtsgrundlagen</h5>
            <p>Die Verarbeitung erfolgt zur Vertragsabwicklung, Kundenkommunikation, Betrugsprävention,
                gesetzlichen Aufbewahrungspflichten und – soweit erforderlich – aufgrund berechtigter Interessen
                an einem sicheren und effizienten Online-Shop (DSGVO / BDSG).</p>

            <h5>4. Weitergabe</h5>
            <p>
                Daten werden nur weitergegeben, soweit dies zur Vertragserfüllung nötig ist
                (z.&nbsp;B. Transportunternehmen, E-Mail-Versand) oder eine gesetzliche Pflicht besteht.
                Eine Verkaufsweitergabe zu Marketingzwecken an Dritte findet nicht statt.
            </p>

            <h5>5. Speicherdauer</h5>
            <p>
                Bestell- und Buchhaltungsdaten werden gemäss gesetzlichen Fristen (in der Regel 10 Jahre) aufbewahrt.
                Anfragen und Logs werden gelöscht, sobald der Zweck entfällt und keine Aufbewahrungspflicht entgegensteht.
            </p>

            <h5>6. Cookies / Session</h5>
            <p>
                Der Shop nutzt technisch notwendige Session-Cookies für Warenkorb, Wunschzettel und Sicherheit (CSRF).
                Optionale Analyse- oder Marketing-Cookies setzen wir nur, wenn dies klar gekennzeichnet und – soweit nötig – eingewilligt wird.
            </p>

            <h5>7. Sicherheit</h5>
            <p>
                Die Website ist über HTTPS erreichbar. Zugriffe und Datenübertragungen erfolgen verschlüsselt.
                Dennoch kann keine absolute Sicherheit im Internet garantiert werden.
            </p>

            <h5>8. Ihre Rechte</h5>
            <p>
                Sie haben nach der DSGVO u.&nbsp;a. Rechte auf Auskunft, Berichtigung,
                Löschung, Einschränkung, Datenübertragbarkeit und Widerspruch. Anfragen an
                <a href="mailto:{{ $nap['email'] }}">{{ $nap['email'] }}</a>.
                Sie können sich zudem an die zuständige Datenschutzaufsichtsbehörde wenden.
            </p>

            <h5>9. Änderungen</h5>
            <p>Wir können diese Erklärung anpassen. Es gilt die jeweils auf dieser Seite veröffentlichte Fassung.</p>
        </div>
    </section>
@endsection
