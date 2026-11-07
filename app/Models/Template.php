<?php

namespace App\Models;

use App\Traits\BelongsToWorkspace;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ImranDev\UniversalSlug\Traits\HasUniversalSlug;

class Template extends Model
{
    use HasFactory, BelongsToWorkspace, HasUniversalSlug;

    protected $fillable = [
        'workspace_id',
        'name',
        'slug',
        'category',
        'marketplace',
        'system_prompt',
        'user_prompt_template',
        'variables',
        'is_system',
        'is_active',
        'version',
    ];

    protected function casts(): array
    {
        return [
            'variables' => 'array',
            'is_system' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function slugSourceField(): string
    {
        return 'name';
    }
}
