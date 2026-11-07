<?php

namespace App\Services\ModuleManager;

use App\Models\Module;
use App\Models\Workspace;
use App\Models\WorkspaceModule;
use Illuminate\Support\Facades\Cache;

class ModuleManager
{
    /**
     * Ensure all 48 registry modules exist in database.
     */
    public function syncRegistryToDatabase(): void
    {
        $all = ModuleRegistry::all();

        foreach ($all as $key => $meta) {
            Module::updateOrCreate(
                ['key' => $key],
                [
                    'name' => $meta['name'],
                    'description' => $meta['description'],
                    'category' => $meta['category'],
                    'version' => '1.0.0',
                    'is_core' => $meta['is_core'],
                    'default_enabled' => $meta['default_enabled'],
                    'dependencies' => $meta['dependencies'],
                    'icon' => $meta['icon'],
                    'is_globally_enabled' => true,
                ]
            );
        }
    }

    /**
     * Check if a module is globally enabled platform-wide.
     */
    public function isGloballyEnabled(string $moduleKey): bool
    {
        $module = Module::find($moduleKey);
        return $module ? (bool) $module->is_globally_enabled : false;
    }

    /**
     * Check if a workspace's subscription plan permits this module.
     */
    public function isPlanAllowed(Workspace $workspace, string $moduleKey): bool
    {
        // Core modules are always allowed
        $meta = ModuleRegistry::all()[$moduleKey] ?? null;
        if ($meta && !empty($meta['is_core'])) {
            return true;
        }

        if (!$workspace->plan) {
            return true; // Default fallback allows all unless restricted
        }

        return $workspace->plan->allowsModule($moduleKey);
    }

    /**
     * Check if the workspace owner has turned this module on.
     */
    public function isWorkspaceEnabled(Workspace $workspace, string $moduleKey): bool
    {
        $meta = ModuleRegistry::all()[$moduleKey] ?? null;
        if ($meta && !empty($meta['is_core'])) {
            return true;
        }

        $record = WorkspaceModule::where('workspace_id', $workspace->id)
            ->where('module_key', $moduleKey)
            ->first();

        if ($record !== null) {
            return (bool) $record->enabled;
        }

        // Fallback to default_enabled
        return $meta ? (bool) $meta['default_enabled'] : false;
    }

    /**
     * Master Check: Is this module active for this workspace right now?
     * Tri-layer check: Global -> Plan -> Workspace Switch.
     */
    public function isModuleActiveForWorkspace(Workspace|int|null $workspace, string $moduleKey): bool
    {
        if ($workspace === null) {
            return $this->isGloballyEnabled($moduleKey);
        }

        if (is_int($workspace)) {
            $workspace = Workspace::find($workspace);
            if (!$workspace) {
                return false;
            }
        }

        // Layer 1: Global Platform Switch
        if (!$this->isGloballyEnabled($moduleKey)) {
            return false;
        }

        // Layer 2: Subscription Plan Permission
        if (!$this->isPlanAllowed($workspace, $moduleKey)) {
            return false;
        }

        // Layer 3: Workspace Owner Switch
        return $this->isWorkspaceEnabled($workspace, $moduleKey);
    }

    /**
     * Enable a module for a workspace with dependency validation.
     * Returns array of [success => bool, message => string, missing_dependencies => array]
     */
    public function enableModuleForWorkspace(Workspace $workspace, string $moduleKey, ?int $userId = null): array
    {
        $meta = ModuleRegistry::all()[$moduleKey] ?? null;
        if (!$meta) {
            return ['success' => false, 'message' => "Module {$moduleKey} does not exist.", 'missing_dependencies' => []];
        }

        // Check global status
        if (!$this->isGloballyEnabled($moduleKey)) {
            return ['success' => false, 'message' => "Module {$moduleKey} is globally disabled by platform administrator.", 'missing_dependencies' => []];
        }

        // Check plan status
        if (!$this->isPlanAllowed($workspace, $moduleKey)) {
            return ['success' => false, 'message' => "Your current subscription plan does not include the {$meta['name']} module.", 'missing_dependencies' => []];
        }

        // Validate dependencies
        $missing = [];
        foreach ($meta['dependencies'] as $depKey) {
            if (!$this->isModuleActiveForWorkspace($workspace, $depKey)) {
                $depMeta = ModuleRegistry::all()[$depKey] ?? null;
                $missing[] = $depMeta ? $depMeta['name'] : $depKey;
            }
        }

        if (!empty($missing)) {
            return [
                'success' => false,
                'message' => "Cannot enable {$meta['name']} until the following required modules are enabled: " . implode(', ', $missing),
                'missing_dependencies' => $missing,
            ];
        }

        WorkspaceModule::updateOrCreate(
            ['workspace_id' => $workspace->id, 'module_key' => $moduleKey],
            [
                'enabled' => true,
                'enabled_at' => now(),
                'enabled_by' => $userId ?? auth()->id(),
            ]
        );

        return ['success' => true, 'message' => "{$meta['name']} enabled successfully.", 'missing_dependencies' => []];
    }

    /**
     * Disable a module for a workspace. Warns if other enabled modules depend on it.
     */
    public function disableModuleForWorkspace(Workspace $workspace, string $moduleKey): array
    {
        $meta = ModuleRegistry::all()[$moduleKey] ?? null;
        if (!$meta) {
            return ['success' => false, 'message' => "Module {$moduleKey} not found.", 'dependents' => []];
        }

        if (!empty($meta['is_core'])) {
            return ['success' => false, 'message' => "Core module {$meta['name']} cannot be disabled.", 'dependents' => []];
        }

        // Check which active modules depend on this one
        $dependents = [];
        foreach (ModuleRegistry::all() as $otherKey => $otherMeta) {
            if (in_array($moduleKey, $otherMeta['dependencies'], true)) {
                if ($this->isModuleActiveForWorkspace($workspace, $otherKey)) {
                    $dependents[] = $otherMeta['name'];
                }
            }
        }

        if (!empty($dependents)) {
            return [
                'success' => false,
                'message' => "Cannot disable {$meta['name']} because the following active modules depend on it: " . implode(', ', $dependents) . ". Please disable them first.",
                'dependents' => $dependents,
            ];
        }

        WorkspaceModule::updateOrCreate(
            ['workspace_id' => $workspace->id, 'module_key' => $moduleKey],
            ['enabled' => false]
        );

        return ['success' => true, 'message' => "{$meta['name']} disabled.", 'dependents' => []];
    }

    /**
     * Apply a persona preset (Solo, Dropshipper, Brand, Agency, Enterprise, Wholesaler).
     */
    public function applyPreset(Workspace $workspace, string $presetKey, ?int $userId = null): void
    {
        $presets = ModuleRegistry::presets();
        $preset = $presets[$presetKey] ?? $presets['solo'];

        $allModules = ModuleRegistry::all();
        $modulesToEnable = $preset['modules'];

        foreach ($allModules as $key => $meta) {
            if (!empty($meta['is_core'])) {
                continue;
            }

            $shouldEnable = in_array($key, $modulesToEnable, true);

            WorkspaceModule::updateOrCreate(
                ['workspace_id' => $workspace->id, 'module_key' => $key],
                [
                    'enabled' => $shouldEnable,
                    'enabled_at' => $shouldEnable ? now() : null,
                    'enabled_by' => $shouldEnable ? ($userId ?? auth()->id()) : null,
                ]
            );
        }
    }
}
