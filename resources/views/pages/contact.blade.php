@extends('layouts.omniva')

@section('title', 'Kontakt')
@section('meta_description', 'Kontaktieren Sie F & B Forst und Brennholz GmbH in Hohenfels: Telefon, E-Mail und Kontaktformular. Mo–Fr 08:00–18:00.')

@section('content')
    @php $nap = company_nap(); @endphp
    @include('partials.page-title', ['pageTitle' => 'Kontakt aufnehmen', 'titleImageSlug' => 'contact-1'])
    <section class="flat-spacing-9">
        <div class="container">
            @php $c = is_file(public_path('media/site/contact-1.jpg')) ? '/media/site/contact-1.jpg' : null; @endphp
            @if ($c)
                <img src="{{ $c }}" alt="Kontakt {{ config('app.name') }}" class="rounded-16 w-100 mb-4" style="max-height:320px;object-fit:cover;">
            @endif
            <div class="row">
                <div class="col-lg-5 mb-4">
                    <h6>Unternehmensdaten</h6>
                    <p class="text-caption">
                        <strong>{{ $nap['legal_name'] }}</strong><br>
                        <strong>Sitz:</strong> {{ $nap['street'] }}, {{ $nap['postal_code'] }} {{ $nap['locality'] ?? $nap['city'] }}, {{ $nap['country_name'] }}
                        @if (! empty($nap['operating_address']['street']))
                            <br><strong>{{ $nap['operating_address']['label'] ?? 'Lager' }}:</strong>
                            {{ $nap['operating_address']['street'] }},
                            {{ $nap['operating_address']['postal_code'] ?? '' }}
                            {{ $nap['operating_address']['locality'] ?? '' }},
                            {{ $nap['operating_address']['country_name'] ?? $nap['country_name'] }}
                        @endif
                    </p>
                    <p class="text-caption">
                        Telefon: <a href="tel:{{ $nap['telephone'] }}">{{ $nap['telephone_display'] }}</a><br>
                        E-Mail: <a href="mailto:{{ $nap['email'] }}">{{ $nap['email'] }}</a><br>
                        {{ $nap['hr_number'] ?? 'HRB 40744' }} · {{ $nap['register_court'] ?? 'Amtsgericht Nürnberg' }}
                        @if (! empty($nap['vat_id']))
                            <br>USt-IdNr.: {{ $nap['vat_id'] }}
                        @endif
                    </p>
                    <p class="text-caption">
                        <strong>Erreichbarkeit:</strong><br>
                        {{ $nap['hours_label'] ?? 'Montag–Freitag, 08:00–18:00 Uhr' }}
                    </p>
                    <p class="text-caption">
                        Wir antworten in der Regel innerhalb eines Werktags.
                        Für Bestellstatus bitte Bestellnummer angeben.
                    </p>

                    <h6 class="mt-4">Weitere Informationen</h6>
                    <ul class="text-caption" style="padding-left:1.1rem;">
                        <li><a href="{{ route('pages.versand') }}">Versand &amp; Lieferzeiten</a></li>
                        <li><a href="{{ route('pages.rueckgabe') }}">Rückgabe &amp; Erstattung</a></li>
                        <li><a href="{{ route('pages.terms') }}">AGB</a></li>
                        <li><a href="{{ route('pages.impressum') }}">Impressum</a></li>
                        <li><a href="{{ route('pages.privacy') }}">Datenschutz</a></li>
                    </ul>

                    @php
                        $wa = preg_replace('/\D+/', '', (string) config('contact.whatsapp'));
                    @endphp
                    @if ($wa)
                        <div class="mt-3">
                            <a class="tf-btn animate-btn" target="_blank" rel="noopener"
                               href="https://wa.me/{{ $wa }}?text={{ rawurlencode((string) config('contact.whatsapp_message', 'Guten Tag, ich habe eine Frage zu Ihren Produkten.')) }}">
                                WhatsApp schreiben
                            </a>
                        </div>
                    @endif
                </div>
                <div class="col-lg-7">
                    <h6>Kontaktformular</h6>
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('pages.contact.submit') }}" class="row g-3">
                        @csrf
                        <div class="col-md-6">
                            <input class="form-control" name="name" value="{{ old('name') }}" placeholder="Name *" required maxlength="120">
                        </div>
                        <div class="col-md-6">
                            <input class="form-control" type="email" name="email" value="{{ old('email') }}" placeholder="E-Mail *" required maxlength="120">
                        </div>
                        <div class="col-md-6">
                            <input class="form-control" type="tel" name="phone" value="{{ old('phone') }}" placeholder="Telefon" maxlength="40">
                        </div>
                        <div class="col-md-6">
                            <input class="form-control" name="subject" value="{{ old('subject') }}" placeholder="Betreff" maxlength="160">
                        </div>
                        <div class="col-12">
                            <textarea class="form-control" name="message" rows="6" placeholder="Ihre Nachricht *" required maxlength="5000">{{ old('message') }}</textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="tf-btn animate-btn">Nachricht senden</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
