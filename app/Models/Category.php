<?php

namespace App\Models;

use App\Traits\BelongsToWorkspace;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use ImranDev\UniversalSlug\Traits\HasUniversalSlug;

class Category extends Model
{
    use HasFactory, BelongsToWorkspace, HasUniversalSlug;

    protected $fillable = [
        'workspace_id',
        'parent_id',
        'name',
        'slug',
        'description',
        'taxonomy_code',
    ];

    public function slugSourceField(): string
    {
        return 'name';
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
