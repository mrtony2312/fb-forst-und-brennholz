@php
    $pay = $pay ?? payment_account();
    $reference = $reference ?? null;
@endphp
<div class="payment-account-box" style="margin-top:16px;padding:16px;border:1px solid #ece7e1;border-radius:10px;background:#faf7f2;">
    <p class="fw-semibold mb-2" style="margin:0 0 8px;">Zahlungsinformationen</p>
    <ul class="text-caption mb-0" style="list-style:none;padding:0;margin:0;line-height:1.7;">
        @if (! empty($pay['beneficiary']))
            <li><strong>Kontoinhaber:</strong> {{ $pay['beneficiary'] }}</li>
        @endif
        @if (! empty($pay['bank_name']))
            <li><strong>Bank:</strong> {{ $pay['bank_name'] }}</li>
        @endif
        @if (! empty($pay['iban']))
            <li><strong>IBAN:</strong> {{ $pay['iban'] }}</li>
        @endif
        @if (! empty($pay['bic']))
            <li><strong>BIC:</strong> {{ $pay['bic'] }}</li>
        @endif
        @if (! empty($pay['address']))
            <li><strong>Adresse:</strong> {{ $pay['address'] }}</li>
        @endif
        @if ($reference)
            <li><strong>Verwendungszweck:</strong> {{ $reference }}</li>
        @endif
        @if (! empty($pay['reference_hint']))
            <li class="mt-1">{{ $pay['reference_hint'] }}</li>
        @endif
    </ul>
</div>
