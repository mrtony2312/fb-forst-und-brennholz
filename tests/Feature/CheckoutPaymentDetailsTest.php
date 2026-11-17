<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ShopSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutPaymentDetailsTest extends TestCase
{
    use RefreshDatabase;

    public function test_confirmation_shows_payment_address_for_vorkasse(): void
    {
        ShopSetting::putGroup('payment_account', [
            'beneficiary' => 'F & B Forst und Brennholz GmbH',
            'bank_name' => '',
            'iban' => '',
            'bic' => '',
            'address' => 'Bahnhofstrasse 2, 5600 Lenzburg AG',
            'reference_hint' => 'Bitte Bestellnummer angeben.',
        ]);

        $product = Product::factory()->create(['price' => 49.90, 'in_stock' => true]);
        ProductImage::query()->create([
            'product_id' => $product->id,
            'path' => 'assets/images/demo/01.jpg',
            'is_primary' => true,
            'position' => 0,
        ]);

        $order = Order::query()->create([
            'reference' => 'TEST-PAY-001',
            'status' => 'pending',
            'email' => 'kunde@example.com',
            'first_name' => 'Max',
            'last_name' => 'Mustermann',
            'phone' => '+491701234567',
            'address' => 'Musterstrasse 1',
            'postcode' => '10115',
            'city' => 'Berlin',
            'country' => 'DE',
            'subtotal' => 49.90,
            'shipping' => 0,
            'total' => 49.90,
            'currency' => 'EUR',
            'payment_method' => 'vorkasse',
        ]);

        $this->get(route('checkout.confirmation', $order->reference))
            ->assertOk()
            ->assertSee('Zahlungsinformationen', false)
            ->assertSee('Bahnhofstrasse 2, 5600 Lenzburg AG', false)
            ->assertSee('Adresse:', false);
    }
}
