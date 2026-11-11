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
        $this->assertSame(19.0, vat_included(119.0));
    }

    public function test_only_enabled_payment_methods_are_offered(): void
    {
        $this->assertSame(['rechnung', 'vorkasse'], array_keys(payment_methods()));
    }

    public function test_pages_show_vat_and_payment_methods(): void
    {
        foreach (['/agb', '/faq', '/warenkorb'] as $url) {
            $this->get($url)->assertOk()->assertSee('19 %', false);
        }
        $this->get('/faq')->assertSee('Rechnung', false)->assertDontSee('TWINT', false);
    }

    public function test_legal_pages_expose_business_identity(): void
    {
        $this->get('/impressum')
            ->assertOk()
            ->assertSee('F & B Forst und Brennholz GmbH', false)
            ->assertSee('HRB 40744', false)
            ->assertSee('Dekan-Schnitter-Straße 4', false)
            ->assertSee('Hohenfels', false)
            ->assertSee('Thomas Josef Freimann', false)
            ->assertSee('Tobias Heinrich Boßle', false)
            ->assertSee('Brennholzhandel', false)
            ->assertSee('Haarziegelhütte 10a', false);

        $this->get('/versand')
            ->assertOk()
            ->assertSee('Deutschland', false)
            ->assertSee('kostenlos', false);

        $this->get('/rueckgabe')
            ->assertOk()
            ->assertSee('14', false)
            ->assertSee('Widerrufsrecht', false);

        $this->get('/kontakt')
            ->assertOk()
            ->assertSee('info@fbforstundbrennholzgmbh.com', false)
            ->assertSee('+49 1525 5641834', false)
            ->assertSee('Haarziegelhütte 10a', false);
    }

    public function test_checkout_stores_payment_method_and_rejects_inactive_ones(): void
    {
        $product = Product::factory()->create(['price' => 100, 'in_stock' => true]);
        $data = [
            'first_name' => 'Anna',
            'last_name' => 'Muster',
            'email' => 'a@example.de',
            'phone' => '+49 30 1234567',
            'address' => 'Hauptstrasse 1',
            'city' => 'Berlin',
            'postcode' => '10115',
            'country' => 'DE',
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
        $this->assertSame('DE', $order->country);
        $this->assertSame(0.0, (float) $order->shipping);
        $this->assertSame('EUR', $order->currency);
    }
}
