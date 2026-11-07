<?php

namespace App\Models;

use Filament\Models\Contracts\HasCurrentTenantLabel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use ImranDev\UniversalSlug\Traits\HasUniversalSlug;

class Workspace extends Model implements HasCurrentTenantLabel
{
    use HasFactory, HasUniversalSlug;

    protected $fillable = [
        'name',
        'slug',
        'owner_id',
        'parent_id',
        'type',
        'plan_id',
        'trial_ends_at',
        'credits_balance',
        'domain',
        'subdomain',
        'brand_settings',
        'currency',
        'timezone',
        'locale',
    ];

    protected function casts(): array
    {
        return [
            'trial_ends_at' => 'datetime',
            'brand_settings' => 'array',
            'credits_balance' => 'integer',
        ];
    }

    /**
     * Slug generation source field for UniversalSlug.
     */
    public function slugSourceField(): string
    {
        return 'name';
    }

    public function getCurrentTenantLabel(): string
    {
        return 'Active Workspace';
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Workspace::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Workspace::class, 'parent_id');
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'workspace_user')
            ->withPivot(['role', 'permissions', 'is_active'])
            ->withTimestamps();
    }

    public function workspaceModules(): HasMany
    {
        return $this->hasMany(WorkspaceModule::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function listings(): HasMany
    {
        return $this->hasMany(ProductListing::class);
    }

    public function bulkJobs(): HasMany
    {
        return $this->hasMany(BulkJob::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function creditTransactions(): HasMany
    {
        return $this->hasMany(CreditTransaction::class);
    }

    public function brandVoices(): HasMany
    {
        return $this->hasMany(BrandVoice::class);
    }

    public function channelConnections(): HasMany
    {
        return $this->hasMany(ChannelConnection::class);
    }

    public function feeds(): HasMany
    {
        return $this->hasMany(Feed::class);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function webhooks(): HasMany
    {
        return $this->hasMany(Webhook::class);
    }

    public function integrations(): HasMany
    {
        return $this->hasMany(Integration::class);
    }

    /**
     * Check if a module is currently active for this workspace.
     */
    public function isModuleActive(string $moduleKey): bool
    {
        return app(\App\Services\ModuleManager\ModuleManager::class)->isModuleActiveForWorkspace($this, $moduleKey);
    }

    /**
     * Deduct credits from the workspace balance.
     */
    public function deductCredits(int $amount, string $description, ?Model $reference = null): bool
    {
        if ($this->credits_balance < $amount) {
            return false;
        }

        $this->decrement('credits_balance', $amount);

        $this->creditTransactions()->create([
            'user_id' => auth()->id(),
            'type' => 'debit',
            'amount' => $amount,
            'balance_after' => $this->credits_balance,
            'description' => $description,
            'reference_type' => $reference ? get_class($reference) : null,
            'reference_id' => $reference?->getKey(),
        ]);

        return true;
    }

    /**
     * Add credits to the workspace balance.
     */
    public function addCredits(int $amount, string $description, ?Model $reference = null): void
    {
        $this->increment('credits_balance', $amount);

        $this->creditTransactions()->create([
            'user_id' => auth()->id(),
            'type' => 'credit',
            'amount' => $amount,
            'balance_after' => $this->credits_balance,
            'description' => $description,
            'reference_type' => $reference ? get_class($reference) : null,
            'reference_id' => $reference?->getKey(),
        ]);
    }
}
