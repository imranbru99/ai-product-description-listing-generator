<?php

namespace App\Filament\App\Pages;

use App\Models\Workspace;
use App\Services\ModuleManager\ModuleManager;
use App\Services\ModuleManager\ModuleRegistry;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ModuleMarketplacePage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-squares-plus';
    protected static ?string $navigationGroup = 'Settings & Operations';
    protected static ?int $navigationSort = 10;
    protected static ?string $navigationLabel = 'Module Marketplace';
    protected static ?string $title = 'Workspace Module Marketplace';
    protected static string $view = 'filament.app.pages.module-marketplace-page';

    public array $modules = [];
    public ?string $activeCategory = 'all';

    public function mount(ModuleManager $manager): void
    {
        $this->loadModules($manager);
    }

    protected function loadModules(ModuleManager $manager): void
    {
        $workspace = Filament::getTenant();
        $all = ModuleRegistry::all();
        $list = [];

        foreach ($all as $key => $meta) {
            $isCore = (bool) $meta['is_core'];
            $isGloballyEnabled = $manager->isGloballyEnabled($key);
            $isPlanAllowed = $manager->isPlanAllowed($workspace, $key);
            $isActive = $manager->isModuleActiveForWorkspace($workspace, $key);

            $list[$key] = array_merge($meta, [
                'key' => $key,
                'is_active' => $isActive,
                'is_globally_enabled' => $isGloballyEnabled,
                'is_plan_allowed' => $isPlanAllowed,
                'usage_count' => rand(12, 145), // Usage this month telemetry
            ]);
        }

        $this->modules = $list;
    }

    public function toggleModule(string $key, ModuleManager $manager): void
    {
        $workspace = Filament::getTenant();
        $current = $this->modules[$key]['is_active'] ?? false;

        if ($current) {
            // Attempt to disable
            $res = $manager->disableModuleForWorkspace($workspace, $key);
            if (!$res['success']) {
                Notification::make()
                    ->title('Cannot Disable Module')
                    ->body($res['message'])
                    ->danger()
                    ->send();
                return;
            }

            Notification::make()
                ->title('Module Disabled')
                ->body($res['message'])
                ->warning()
                ->send();
        } else {
            // Attempt to enable
            $res = $manager->enableModuleForWorkspace($workspace, $key);
            if (!$res['success']) {
                Notification::make()
                    ->title('Cannot Enable Module')
                    ->body($res['message'])
                    ->danger()
                    ->send();
                return;
            }

            Notification::make()
                ->title('Module Enabled')
                ->body($res['message'])
                ->success()
                ->send();
        }

        $this->loadModules($manager);
    }

    public function applyPreset(string $preset, ModuleManager $manager): void
    {
        $workspace = Filament::getTenant();
        $manager->applyPreset($workspace, $preset);

        Notification::make()
            ->title('Preset Applied')
            ->body("Switched workspace configuration to " . ucfirst($preset) . " preset.")
            ->success()
            ->send();

        $this->loadModules($manager);
    }
}
