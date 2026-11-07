<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use ImranDev\UniversalSlug\Traits\HasUniversalSlug;

class Article extends Model
{
    use HasFactory, HasUniversalSlug;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'locale',
        'excerpt',
        'content',
        'status',
        'views_count',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'views_count' => 'integer',
            'is_featured' => 'boolean',
        ];
    }

    public function slugSourceField(): string
    {
        return 'title';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ArticleCategory::class);
    }
}
