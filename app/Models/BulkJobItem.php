<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BulkJobItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'bulk_job_id',
        'product_id',
        'generated_listing_id',
        'status',
        'error_message',
        'tokens_used',
        'cost',
    ];

    protected function casts(): array
    {
        return [
            'tokens_used' => 'integer',
            'cost' => 'decimal:4',
        ];
    }

    public function bulkJob(): BelongsTo
    {
        return $this->belongsTo(BulkJob::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function generatedListing(): BelongsTo
    {
        return $this->belongsTo(ProductListing::class, 'generated_listing_id');
    }
}
