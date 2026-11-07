<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ListingVersion extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'product_listing_id',
        'user_id',
        'version_number',
        'title',
        'short_description',
        'long_description',
        'bullet_points',
        'change_summary',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'bullet_points' => 'array',
            'version_number' => 'integer',
            'created_at' => 'datetime',
        ];
    }

    public function productListing(): BelongsTo
    {
        return $this->belongsTo(ProductListing::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
