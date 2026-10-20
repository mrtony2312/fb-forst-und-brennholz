<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Historical default was FR (template leftover) — shop is CH-only.
        if (Schema::hasTable('orders')) {
            DB::table('orders')->where('country', 'FR')->update(['country' => 'CH']);
            DB::table('orders')->whereNull('country')->orWhere('country', '')->update(['country' => 'CH']);
        }
    }

    public function down(): void
    {
        // no-op: do not restore incorrect FR default
    }
};
