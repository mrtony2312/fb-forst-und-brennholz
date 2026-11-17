@extends('admin.layout')

@section('title', 'Zahlungskonto')

@section('content')
    <div class="row">
        <h1 style="margin:0;font-size:22px;">Zahlungskonto</h1>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="btn btn-line">Abmelden</button>
        </form>
    </div>

    @if (session('status'))
        <div class="flash">{{ session('status') }}</div>
    @endif

    <div class="card">
        <p class="hint" style="margin-top:0;">
            Diese Angaben erscheinen auf der Bestellbestätigung und in der Kunden-E-Mail
            (Vorkasse / Rechnung).
        </p>

        <form method="POST" action="{{ route('admin.payment.update') }}">
            @csrf
            @method('PUT')

            <label for="beneficiary">Kontoinhaber</label>
            <input id="beneficiary" type="text" name="beneficiary" value="{{ old('beneficiary', $account['beneficiary']) }}" required>
            @error('beneficiary')<div class="error">{{ $message }}</div>@enderror

            <label for="bank_name">Bank</label>
            <input id="bank_name" type="text" name="bank_name" value="{{ old('bank_name', $account['bank_name']) }}">
            @error('bank_name')<div class="error">{{ $message }}</div>@enderror

            <label for="iban">IBAN</label>
            <input id="iban" type="text" name="iban" value="{{ old('iban', $account['iban']) }}" placeholder="DE00 0000 0000 0000 0000 00">
            @error('iban')<div class="error">{{ $message }}</div>@enderror

            <label for="bic">BIC</label>
            <input id="bic" type="text" name="bic" value="{{ old('bic', $account['bic']) }}">
            @error('bic')<div class="error">{{ $message }}</div>@enderror

            <label for="address">Adresse</label>
            <input id="address" type="text" name="address" value="{{ old('address', $account['address']) }}" required>
            <div class="hint">Beispiel: Bahnhofstrasse 2, 5600 Lenzburg AG</div>
            @error('address')<div class="error">{{ $message }}</div>@enderror

            <label for="reference_hint">Hinweis Verwendungszweck</label>
            <input id="reference_hint" type="text" name="reference_hint" value="{{ old('reference_hint', $account['reference_hint']) }}">
            @error('reference_hint')<div class="error">{{ $message }}</div>@enderror

            <div style="margin-top:20px;">
                <button type="submit">Speichern</button>
            </div>
        </form>
    </div>
@endsection
