<?php

namespace App\Models;

use App\Traits\BelongsToWorkspace;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BulkJob extends Model
{
    use HasFactory, BelongsToWorkspace;

    protected $fillable = [
        'workspace_id',
        'user_id',
        'template_id',
        'name',
        'marketplace',
        'locale',
        'status',
        'total_items',
        'processed_items',
        'failed_items',
        'estimated_cost',
        'actual_cost',
        'settings',
        'error_log',
        'started_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'total_items' => 'integer',
            'processed_items' => 'integer',
            'failed_items' => 'integer',
            'estimated_cost' => 'decimal:4',
            'actual_cost' => 'decimal:4',
            'settings' => 'array',
            'error_log' => 'array',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(BulkJobItem::class);
    }

    public function getProgressPercentAttribute(): int
    {
        if ($this->total_items <= 0) {
            return 0;
        }

        return (int) round(($this->processed_items / $this->total_items) * 100);
    }
}
