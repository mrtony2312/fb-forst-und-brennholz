<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    protected $guarded = [];

    protected $casts = ['is_primary' => 'boolean'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /** Public web URL for the stored asset file (root-relative so local hosts always match). */
    public function getUrlAttribute(): string
    {
        // stored as "assets/images/<cat>/<slug>/01.jpg" -> served from public/media/...
        $rel = preg_replace('#^assets/images/#', '', (string) $this->path);

        return '/media/'.ltrim($rel, '/');
    }
}
