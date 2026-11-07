<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use ImranDev\UniversalSlug\Traits\HasUniversalSlug;

class ArticleCategory extends Model
{
    use HasFactory, HasUniversalSlug;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'description',
    ];

    public function slugSourceField(): string
    {
        return 'name';
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'category_id');
    }
}
