<?php

namespace App\Http\Controllers;

use App\Mail\NewOrderNotificationMail;
use App\Mail\OrderConfirmationMail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CheckoutController extends Controller
{
    public function __construct(private readonly Cart $cart) {}

    public function index()
    {
        $items = $this->cart->items();
        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('status', 'Ihr Warenkorb ist leer.');
        }

        return view('checkout.index', [
            'items' => $items,
            'cart' => $this->cart,
        ]);
    }

    public function store(Request $request)
    {
        $items = $this->cart->items();
        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('status', 'Ihr Warenkorb ist leer.');
        }

        $outOfStock = $items->filter(fn ($item) => ! $item['product']->in_stock);
        if ($outOfStock->isNotEmpty()) {
            $names = $outOfStock->map(fn ($item) => $item['product']->name)->join(', ');

            return redirect()->route('cart.index')
                ->with('error', 'Folgende Artikel sind nicht mehr verfügbar: '.$names.'. Bitte entfernen Sie diese aus dem Warenkorb.');
        }

        $allowedPayments = array_keys(payment_methods());

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:80'],
            'last_name' => ['required', 'string', 'max:80'],
            'email' => ['required', 'email', 'max:120'],
            'phone' => ['required', 'string', 'max:40'],
            'company' => ['nullable', 'string', 'max:120'],
            'address' => ['required', 'string', 'max:180'],
            'address_2' => ['nullable', 'string', 'max:180'],
            'postcode' => ['required', 'string', 'max:12'],
            'city' => ['required', 'string', 'max:80'],
            'country' => ['nullable', 'string', 'max:2'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'payment_method' => ['required', 'string', Rule::in($allowedPayments)],
        ], [
            'payment_method.in' => 'Bitte wählen Sie eine verfügbare Zahlungsart.',
            'phone.required' => 'Bitte geben Sie eine Telefonnummer für die Lieferkoordination an.',
        ]);

        // Shop delivers only within Switzerland — never trust a foreign country code.
        $data['country'] = 'CH';
        $data['phone'] = trim((string) $data['phone']);
        $data['company'] = isset($data['company']) ? trim((string) $data['company']) : null;
        $data['address_2'] = isset($data['address_2']) ? trim((string) $data['address_2']) : null;
        $data['notes'] = isset($data['notes']) ? trim((string) $data['notes']) : null;

        $order = DB::transaction(function () use ($data, $items) {
            $order = Order::create([
                'reference' => strtoupper('HB-'.now()->format('ymd').'-'.Str::upper(Str::random(6))),
                'status' => 'pending',
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'company' => $data['company'] ?: null,
                'address' => $data['address'],
                'address_2' => $data['address_2'] ?: null,
                'postcode' => $data['postcode'],
                'city' => $data['city'],
                'country' => 'CH',
                'notes' => $data['notes'] ?: null,
                'payment_method' => $data['payment_method'],
                'subtotal' => $this->cart->subtotal(),
                'shipping' => $this->cart->shipping(),
                'total' => $this->cart->total(),
                'currency' => 'CHF',
            ]);

            foreach ($items as $item) {
                /** @var \App\Models\Product $product */
                $product = $item['product'];
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'sku' => $product->sku,
                    'name' => $product->name,
                    'unit_price' => $product->price,
                    'quantity' => $item['quantity'],
                    'line_total' => $item['line_total'],
                ]);
            }

            return $order;
        });

        $this->cart->clear();
        $order->load('items');
        $this->sendOrderMails($order);

        return redirect()->route('checkout.confirmation', $order->reference);
    }

    /**
     * Bestätigung an den Kunden + Benachrichtigung an die Bestellannahme.
     * Ein Mailfehler darf die Bestellbestätigungsseite nicht blockieren.
     */
    private function sendOrderMails(Order $order): void
    {
        try {
            Mail::to($order->email, $order->full_name)
                ->send(new OrderConfirmationMail($order));
        } catch (\Throwable $e) {
            Log::error('Bestellbestätigung an Kunde fehlgeschlagen', [
                'order' => $order->reference,
                'error' => $e->getMessage(),
            ]);
        }

        $merchant = (string) (company_nap()['email'] ?? config('mail.from.address'));
        if ($merchant !== '') {
            try {
                Mail::to($merchant)->send(new NewOrderNotificationMail($order));
            } catch (\Throwable $e) {
                Log::error('Bestellbenachrichtigung intern fehlgeschlagen', [
                    'order' => $order->reference,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    public function confirmation(string $reference)
    {
        $order = Order::with('items')->where('reference', $reference)->firstOrFail();

        return view('checkout.confirmation', compact('order'));
    }
}
