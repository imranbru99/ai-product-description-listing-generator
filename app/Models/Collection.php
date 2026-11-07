<?php

namespace App\Models;

use App\Traits\BelongsToWorkspace;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ImranDev\UniversalSlug\Traits\HasUniversalSlug;

class Collection extends Model
{
    use HasFactory, BelongsToWorkspace, HasUniversalSlug;

    protected $fillable = [
        'workspace_id',
        'name',
        'slug',
        'description',
    ];

    public function slugSourceField(): string
    {
        return 'name';
    }
}
