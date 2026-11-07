<?php

namespace App\Models;

use App\Traits\BelongsToWorkspace;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use ImranDev\UniversalSlug\Traits\HasUniversalSlug;

class Brand extends Model
{
    use HasFactory, BelongsToWorkspace, HasUniversalSlug;

    protected $fillable = [
        'workspace_id',
        'name',
        'slug',
        'logo',
        'website',
        'voice_guidelines',
    ];

    public function slugSourceField(): string
    {
        return 'name';
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function brandVoices(): HasMany
    {
        return $this->hasMany(BrandVoice::class);
    }
}
