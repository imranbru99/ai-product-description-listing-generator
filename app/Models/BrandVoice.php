<?php

namespace App\Models;

use App\Traits\BelongsToWorkspace;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BrandVoice extends Model
{
    use HasFactory, BelongsToWorkspace;

    protected $fillable = [
        'workspace_id',
        'brand_id',
        'name',
        'tone',
        'reading_level',
        'banned_words',
        'required_words',
        'style_examples',
        'is_default',
    ];

    protected function casts(): array
    {
        return [
            'banned_words' => 'array',
            'required_words' => 'array',
            'is_default' => 'boolean',
        ];
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }
}
