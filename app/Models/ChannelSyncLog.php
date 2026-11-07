<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChannelSyncLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'channel_connection_id',
        'product_id',
        'product_listing_id',
        'action',
        'status',
        'payload',
        'error_message',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
        ];
    }

    public function connection(): BelongsTo
    {
        return $this->belongsTo(ChannelConnection::class, 'channel_connection_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function listing(): BelongsTo
    {
        return $this->belongsTo(ProductListing::class, 'product_listing_id');
    }
}
