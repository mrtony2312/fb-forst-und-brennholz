<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutPaymentVatTest extends TestCase
{
    use RefreshDatabase;

    public function test_vat_is_extracted_from_gross_price(): void
    {
        $this->assertSame(8.10, vat_included(108.10));
    }

    public function test_only_enabled_payment_methods_are_offered(): void
    {
        $this->assertSame(['rechnung', 'vorkasse'], array_keys(payment_methods()));
    }

    public function test_pages_show_vat_and_payment_methods(): void
    {
        foreach (['/agb', '/faq', '/warenkorb'] as $url) {
            $this->get($url)->assertOk()->assertSee('8.1 %', false);
        }
        $this->get('/faq')->assertSee('QR-Rechnung', false)->assertDontSee('TWINT', false);
    }

    public function test_legal_pages_expose_business_identity(): void
    {
        $this->get('/impressum')
            ->assertOk()
            ->assertSee('Heri Brennholz GmbH', false)
            ->assertSee('CHE-228.719.493', false)
            ->assertSee('Fiderholzstrasse 7', false)
            ->assertSee('Biberist', false);

        $this->get('/versand')
            ->assertOk()
            ->assertSee('Schweiz', false)
            ->assertSee('kostenlos', false);

        $this->get('/rueckgabe')
            ->assertOk()
            ->assertSee('14', false)
            ->assertSee('kein gesetzliches Widerrufsrecht', false);

        $this->get('/kontakt')
            ->assertOk()
            ->assertSee('kontakt@heribrennholzgmbh.com', false)
            ->assertSee('+41 78 609 95 16', false);
    }

    public function test_checkout_stores_payment_method_and_rejects_inactive_ones(): void
    {
        $product = Product::factory()->create(['price' => 100, 'in_stock' => true]);
        $data = [
            'first_name' => 'Anna',
            'last_name' => 'Muster',
            'email' => 'a@example.ch',
            'phone' => '+41 79 123 45 67',
            'address' => 'Bahnhofstrasse 1',
            'city' => 'Bern',
            'postcode' => '3000',
            'country' => 'CH',
        ];

        $this->withSession(['cart' => [$product->id => 1]])
            ->post('/kasse', $data + ['payment_method' => 'twint'])
            ->assertSessionHasErrors('payment_method');

        $this->withSession(['cart' => [$product->id => 1]])
            ->post('/kasse', $data + ['payment_method' => 'vorkasse'])
            ->assertRedirect();

        $order = Order::first();
        $this->assertNotNull($order);
        $this->assertSame('vorkasse', $order->payment_method);
        $this->assertSame('CH', $order->country);
        $this->assertSame(0.0, (float) $order->shipping);
        $this->assertSame('CHF', $order->currency);
    }
}
