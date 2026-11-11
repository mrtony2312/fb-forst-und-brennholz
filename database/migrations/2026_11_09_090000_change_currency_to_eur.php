<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('products')->where('currency', 'CHF')->update(['currency' => 'EUR']);
        DB::table('orders')->where('currency', 'CHF')->update(['currency' => 'EUR']);

        // Grundpreis labels stored as CHF/kg, CHF/Ster, etc.
        $products = DB::table('products')
            ->whereNotNull('price_per_unit_label')
            ->where('price_per_unit_label', 'like', 'CHF/%')
            ->get(['id', 'price_per_unit_label']);

        foreach ($products as $product) {
            DB::table('products')->where('id', $product->id)->update([
                'price_per_unit_label' => preg_replace('/^CHF\//', 'EUR/', (string) $product->price_per_unit_label),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('products')->where('currency', 'EUR')->update(['currency' => 'CHF']);
        DB::table('orders')->where('currency', 'EUR')->update(['currency' => 'CHF']);

        $products = DB::table('products')
            ->whereNotNull('price_per_unit_label')
            ->where('price_per_unit_label', 'like', 'EUR/%')
            ->get(['id', 'price_per_unit_label']);

        foreach ($products as $product) {
            DB::table('products')->where('id', $product->id)->update([
                'price_per_unit_label' => preg_replace('/^EUR\//', 'CHF/', (string) $product->price_per_unit_label),
            ]);
        }
    }
};
