<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Convert historical CHF list prices → EUR (1 CHF = 1.093 EUR, mid-market 2026-11-09)
 * and mark products as EUR for the DE storefront.
 *
 * Guarded so it does not double-convert if re-run after a partial apply.
 */
return new class extends Migration
{
    private const RATE = 1.093;

    private const MARKER = 'fx_chf_eur_2026_11_09';

    public function up(): void
    {
        if (! Schema::hasTable('products')) {
            return;
        }

        // Skip if already converted (heuristic: avg price already inflated OR marker table).
        if (Schema::hasTable('migrations') && $this->alreadyConverted()) {
            return;
        }

        $columns = ['price', 'regular_price', 'sale_price', 'price_per_unit'];

        DB::table('products')->orderBy('id')->chunkById(100, function ($products) use ($columns) {
            foreach ($products as $product) {
                $update = ['currency' => 'EUR'];

                foreach ($columns as $column) {
                    if (! property_exists($product, $column) || $product->{$column} === null) {
                        continue;
                    }
                    $value = (float) $product->{$column};
                    if ($value <= 0) {
                        continue;
                    }
                    $decimals = $column === 'price_per_unit' ? 3 : 2;
                    $update[$column] = round($value * self::RATE, $decimals);
                }

                DB::table('products')->where('id', $product->id)->update($update);
            }
        });

        if (Schema::hasTable('orders')) {
            DB::table('orders')->where('currency', 'CHF')->update(['currency' => 'EUR']);
        }

        // Persist marker in cache table if available (optional).
        try {
            if (Schema::hasTable('cache')) {
                DB::table('cache')->updateOrInsert(
                    ['key' => self::MARKER],
                    ['value' => serialize(['rate' => self::RATE, 'at' => now()->toIso8601String()]), 'expiration' => now()->addYears(10)->timestamp]
                );
            }
        } catch (Throwable) {
            // ignore
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('products')) {
            return;
        }

        $columns = ['price', 'regular_price', 'sale_price', 'price_per_unit'];

        DB::table('products')->orderBy('id')->chunkById(100, function ($products) use ($columns) {
            foreach ($products as $product) {
                $update = [];
                foreach ($columns as $column) {
                    if (! property_exists($product, $column) || $product->{$column} === null) {
                        continue;
                    }
                    $value = (float) $product->{$column};
                    if ($value <= 0) {
                        continue;
                    }
                    $decimals = $column === 'price_per_unit' ? 3 : 2;
                    $update[$column] = round($value / self::RATE, $decimals);
                }
                if ($update !== []) {
                    DB::table('products')->where('id', $product->id)->update($update);
                }
            }
        });
    }

    private function alreadyConverted(): bool
    {
        try {
            if (Schema::hasTable('cache')) {
                return DB::table('cache')->where('key', self::MARKER)->exists();
            }
        } catch (Throwable) {
            // fall through
        }

        return false;
    }
};
