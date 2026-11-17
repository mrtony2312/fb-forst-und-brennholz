<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShopSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentAccountController extends Controller
{
    public function edit(): View
    {
        return view('admin.payment-account', [
            'account' => payment_account(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'beneficiary' => ['required', 'string', 'max:190'],
            'bank_name' => ['nullable', 'string', 'max:190'],
            'iban' => ['nullable', 'string', 'max:64'],
            'bic' => ['nullable', 'string', 'max:32'],
            'address' => ['required', 'string', 'max:255'],
            'reference_hint' => ['nullable', 'string', 'max:255'],
        ]);

        ShopSetting::putGroup('payment_account', [
            'beneficiary' => trim($data['beneficiary']),
            'bank_name' => trim((string) ($data['bank_name'] ?? '')),
            'iban' => strtoupper(preg_replace('/\s+/', '', (string) ($data['iban'] ?? '')) ?? ''),
            'bic' => strtoupper(trim((string) ($data['bic'] ?? ''))),
            'address' => trim($data['address']),
            'reference_hint' => trim((string) ($data['reference_hint'] ?? '')),
        ]);

        return redirect()
            ->route('admin.payment.edit')
            ->with('status', 'Zahlungskonto gespeichert.');
    }
}
