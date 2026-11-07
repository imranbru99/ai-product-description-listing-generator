<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use ImranDev\UniversalSlug\Traits\HasUniversalSlug;

class Plan extends Model
{
    use HasFactory, HasUniversalSlug;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price_monthly',
        'price_yearly',
        'monthly_credits',
        'allowed_modules',
        'max_products',
        'max_users',
        'max_workspaces',
        'is_active',
        'is_popular',
    ];

    protected function casts(): array
    {
        return [
            'allowed_modules' => 'array',
            'is_active' => 'boolean',
            'is_popular' => 'boolean',
            'price_monthly' => 'decimal:2',
            'price_yearly' => 'decimal:2',
            'monthly_credits' => 'integer',
            'max_products' => 'integer',
            'max_users' => 'integer',
            'max_workspaces' => 'integer',
        ];
    }

    public function slugSourceField(): string
    {
        return 'name';
    }

    public function workspaces(): HasMany
    {
        return $this->hasMany(Workspace::class);
    }

    /**
     * Check if a module key is permitted under this plan.
     */
    public function allowsModule(string $moduleKey): bool
    {
        if (empty($this->allowed_modules) || in_array('*', $this->allowed_modules, true)) {
            return true;
        }

        return in_array($moduleKey, $this->allowed_modules, true);
    }
}
