<?php

namespace App\Http\Middleware;

use App\Models\Workspace;
use App\Services\ModuleManager\ModuleManager;
use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureModuleEnabled
{
    public function __construct(
        protected ModuleManager $moduleManager
    ) {}

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $module): Response
    {
        $workspace = null;

        // Try Filament tenant first
        if (class_exists(Filament::class)) {
            $tenant = Filament::getTenant();
            if ($tenant instanceof Workspace) {
                $workspace = $tenant;
            }
        }

        // Try request route parameter
        if (!$workspace && $request->route('workspace')) {
            $param = $request->route('workspace');
            $workspace = $param instanceof Workspace ? $param : Workspace::find($param);
        }

        // Try authenticated user's current workspace
        if (!$workspace && auth()->check() && auth()->user()->current_workspace_id) {
            $workspace = auth()->user()->currentWorkspace;
        }

        // If Super Admin on superadmin panel, permit access
        if (auth()->check() && auth()->user()->isSuperAdmin() && $request->is('superadmin*')) {
            return $next($request);
        }

        if (!$this->moduleManager->isModuleActiveForWorkspace($workspace, $module)) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'error' => 'module_inactive',
                    'message' => "The module '{$module}' is currently inactive for this workspace.",
                ], 403);
            }

            abort(403, "The requested feature ('{$module}') is not enabled on your workspace or subscription plan.");
        }

        return $next($request);
    }
}
