<?php

namespace App\Models;

use App\Traits\BelongsToWorkspace;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChannelConnection extends Model
{
    use HasFactory, BelongsToWorkspace;

    protected $fillable = [
        'workspace_id',
        'channel',
        'name',
        'credentials',
        'field_mapping',
        'settings',
        'status',
        'last_synced_at',
    ];

    protected function casts(): array
    {
        return [
            'credentials' => 'encrypted:array',
            'field_mapping' => 'array',
            'settings' => 'array',
            'last_synced_at' => 'datetime',
        ];
    }

    public function syncLogs(): HasMany
    {
        return $this->hasMany(ChannelSyncLog::class);
    }
}
