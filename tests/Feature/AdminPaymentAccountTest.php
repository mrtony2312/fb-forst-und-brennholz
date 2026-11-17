<?php

namespace Tests\Feature;

use App\Models\ShopSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPaymentAccountTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_payment_settings(): void
    {
        $this->get(route('admin.payment.edit'))->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_update_payment_account_address(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => 'secret123',
        ]);

        $this->actingAs($admin)
            ->put(route('admin.payment.update'), [
                'beneficiary' => 'F & B Forst und Brennholz GmbH',
                'bank_name' => 'Beispielbank',
                'iban' => 'DE89370400440532013000',
                'bic' => 'COBADEFFXXX',
                'address' => 'Bahnhofstrasse 2, 5600 Lenzburg AG',
                'reference_hint' => 'Bestellnummer angeben',
            ])
            ->assertRedirect(route('admin.payment.edit'));

        $stored = ShopSetting::getGroup('payment_account');
        $this->assertSame('Bahnhofstrasse 2, 5600 Lenzburg AG', $stored['address']);
        $this->assertSame('DE89370400440532013000', $stored['iban']);
    }
}
