<?php

namespace App\Traits;

use App\Models\Workspace;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToWorkspace
{
    /**
     * Boot the trait and apply the workspace tenant scope.
     */
    protected static function bootBelongsToWorkspace(): void
    {
        static::creating(function (Model $model) {
            if (empty($model->workspace_id) && static::resolveCurrentWorkspaceId()) {
                $model->workspace_id = static::resolveCurrentWorkspaceId();
            }
        });

        static::addGlobalScope('workspace', function (Builder $builder) {
            $workspaceId = static::resolveCurrentWorkspaceId();
            if ($workspaceId !== null) {
                $builder->where($builder->getModel()->getTable() . '.workspace_id', $workspaceId);
            }
        });
    }

    /**
     * Resolve the current workspace ID from session, auth user, or Filament tenant context.
     */
    public static function resolveCurrentWorkspaceId(): ?int
    {
        // Filament tenant context
        if (class_exists(\Filament\Facades\Filament::class)) {
            $tenant = \Filament\Facades\Filament::getTenant();
            if ($tenant instanceof Workspace) {
                return (int) $tenant->id;
            }
        }

        // Session context
        if (session()->has('current_workspace_id')) {
            return (int) session('current_workspace_id');
        }

        // Auth user current workspace
        if (auth()->check() && auth()->user()->current_workspace_id) {
            return (int) auth()->user()->current_workspace_id;
        }

        return null;
    }

    /**
     * Get the workspace that owns the model.
     */
    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    /**
     * Scope a query to only include items for a specific workspace.
     */
    public function scopeForWorkspace(Builder $query, int|Workspace $workspace): Builder
    {
        $id = $workspace instanceof Workspace ? $workspace->id : $workspace;
        return $query->withoutGlobalScope('workspace')->where($this->getTable() . '.workspace_id', $id);
    }
}
