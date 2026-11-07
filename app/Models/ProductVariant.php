<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'title',
        'sku',
        'barcode',
        'price',
        'inventory_count',
        'option_values',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'inventory_count' => 'integer',
            'option_values' => 'array',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
