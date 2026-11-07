<?php

namespace App\Models;

use App\Traits\BelongsToWorkspace;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ImranDev\UniversalSlug\Traits\HasUniversalSlug;

class Feed extends Model
{
    use HasFactory, BelongsToWorkspace, HasUniversalSlug;

    protected $fillable = [
        'workspace_id',
        'name',
        'slug',
        'type',
        'token',
        'marketplace',
        'filters',
        'file_path',
        'item_count',
        'regenerated_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'filters' => 'array',
            'item_count' => 'integer',
            'regenerated_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function slugSourceField(): string
    {
        return 'name';
    }

    /**
     * Generate secure public feed URL.
     */
    public function getFeedUrlAttribute(): string
    {
        return route('feeds.public', ['token' => $this->token]);
    }
}
