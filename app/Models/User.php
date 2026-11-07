<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasTenants
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'locale',
        'timezone',
        'current_workspace_id',
        'two_factor_secret',
        'two_factor_confirmed_at',
        'phone',
        'is_platform_admin',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
            'last_login_at' => 'datetime',
            'is_platform_admin' => 'boolean',
        ];
    }

    /**
     * Workspaces the user belongs to.
     */
    public function workspaces(): BelongsToMany
    {
        return $this->belongsToMany(Workspace::class, 'workspace_user')
            ->withPivot(['role', 'permissions', 'is_active'])
            ->withTimestamps();
    }

    /**
     * Workspaces owned by the user.
     */
    public function ownedWorkspaces(): HasMany
    {
        return $this->hasMany(Workspace::class, 'owner_id');
    }

    /**
     * Current active workspace for the user.
     */
    public function currentWorkspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class, 'current_workspace_id');
    }

    /**
     * Tasks assigned to the user.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    /**
     * Affiliate profile if user is a partner/affiliate.
     */
    public function affiliate(): HasMany
    {
        return $this->hasMany(Affiliate::class);
    }

    /**
     * Check if user is a platform super administrator.
     */
    public function isSuperAdmin(): bool
    {
        return (bool) $this->is_platform_admin || $this->hasRole('Super Admin');
    }

    /**
     * Check if user is platform staff.
     */
    public function isPlatformStaff(): bool
    {
        return $this->isSuperAdmin() || $this->hasAnyRole(['Support Agent', 'Finance Manager', 'Content Moderator', 'Sales/CRM Manager']);
    }

    /**
     * Check user's role in a specific workspace.
     */
    public function roleInWorkspace(Workspace|int $workspace): ?string
    {
        $workspaceId = $workspace instanceof Workspace ? $workspace->id : $workspace;
        $pivot = $this->workspaces()->where('workspaces.id', $workspaceId)->first()?->pivot;
        return $pivot?->role;
    }

    /**
     * Determine if user has a specific role in a workspace.
     */
    public function hasWorkspaceRole(Workspace|int $workspace, string|array $roles): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        $roles = (array) $roles;
        $userRole = $this->roleInWorkspace($workspace);

        return $userRole !== null && in_array($userRole, $roles, true);
    }

    /**
     * Filament Panel Authorization.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        $panelId = $panel->getId();

        return match ($panelId) {
            'superadmin' => $this->isPlatformStaff(),
            'app' => $this->workspaces()->exists() || $this->isSuperAdmin(),
            'client' => $this->hasRole('Client User') || $this->workspaces()->wherePivot('role', 'Client User')->exists() || $this->isSuperAdmin(),
            'partner' => $this->hasRole('Partner/Affiliate') || Affiliate::where('user_id', $this->id)->exists() || $this->isSuperAdmin(),
            default => true,
        };
    }

    /**
     * Filament Multi-tenancy: Return workspaces accessible by user.
     */
    public function getTenants(Panel $panel): array|Collection
    {
        if ($this->isSuperAdmin()) {
            return Workspace::all();
        }

        return $this->workspaces()->where('workspace_user.is_active', true)->get();
    }

    /**
     * Filament Multi-tenancy: Check if user can access a specific tenant.
     */
    public function canAccessTenant(Model $tenant): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return $this->workspaces()
            ->where('workspaces.id', $tenant->id)
            ->where('workspace_user.is_active', true)
            ->exists();
    }
}
