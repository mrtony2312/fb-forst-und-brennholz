<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ShopSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * @return array<string, mixed>
     */
    protected function casts(): array
    {
        return [
            'value' => 'array',
        ];
    }

    /**
     * @param  array<string, mixed>  $defaults
     * @return array<string, mixed>
     */
    public static function getGroup(string $key, array $defaults = []): array
    {
        /** @var array<string, mixed> $stored */
        $stored = Cache::remember('shop_setting.'.$key, 600, function () use ($key) {
            $row = static::query()->where('key', $key)->first();

            return is_array($row?->value) ? $row->value : [];
        });

        return array_merge($defaults, array_filter(
            $stored,
            static fn ($value) => $value !== null && $value !== ''
        ));
    }

    /**
     * @param  array<string, mixed>  $value
     */
    public static function putGroup(string $key, array $value): void
    {
        static::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        Cache::forget('shop_setting.'.$key);
    }
}
