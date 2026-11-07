<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
{
    use HasFactory;

    protected $primaryKey = 'key';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'key',
        'name',
        'description',
        'category',
        'version',
        'is_core',
        'default_enabled',
        'dependencies',
        'icon',
        'screenshots',
        'is_globally_enabled',
    ];

    protected function casts(): array
    {
        return [
            'is_core' => 'boolean',
            'default_enabled' => 'boolean',
            'is_globally_enabled' => 'boolean',
            'dependencies' => 'array',
            'screenshots' => 'array',
        ];
    }

    public function workspaceModules(): HasMany
    {
        return $this->hasMany(WorkspaceModule::class, 'module_key', 'key');
    }
}
