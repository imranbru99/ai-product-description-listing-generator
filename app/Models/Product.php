<?php

namespace App\Models;

use App\Traits\BelongsToWorkspace;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use ImranDev\UniversalSlug\Traits\HasUniversalSlug;

class Product extends Model
{
    use HasFactory, BelongsToWorkspace, HasUniversalSlug;

    protected $fillable = [
        'workspace_id',
        'category_id',
        'brand_id',
        'name',
        'slug',
        'sku',
        'barcode',
        'status',
        'price',
        'cost_price',
        'currency',
        'tags',
        'attributes',
        'custom_fields',
        'source_url',
        'last_scraped_at',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'cost_price' => 'decimal:2',
            'tags' => 'array',
            'attributes' => 'array',
            'custom_fields' => 'array',
            'last_scraped_at' => 'datetime',
        ];
    }

    public function slugSourceField(): string
    {
        return 'name';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function listings(): HasMany
    {
        return $this->hasMany(ProductListing::class);
    }

    public function primaryImage(): ?ProductImage
    {
        return $this->images()->where('is_primary', true)->first() ?? $this->images()->first();
    }
}
