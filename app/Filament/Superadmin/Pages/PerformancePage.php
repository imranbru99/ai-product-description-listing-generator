<?php

namespace App\Filament\Superadmin\Pages;

use App\Services\Performance\DrutogotiService;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class PerformancePage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-bolt';
    protected static ?string $navigationGroup = 'System Health';
    protected static ?string $navigationLabel = 'Performance & DrutoGoti';
    protected static ?string $title = '⚡ DrutoGoti Speed & Diagnostics';
    protected static string $view = 'filament.superadmin.pages.performance-page';

    public array $diagnostics = [];
    public array $slowQueries = [];
    public array $suggestions = [];

    public function mount(DrutogotiService $service): void
    {
        $this->diagnostics = $service->getDiagnostics();
        $this->slowQueries = $service->getSlowQueries();
        $this->suggestions = $service->getOptimizationSuggestions();
    }

    public function triggerImageScan(DrutogotiService $service): void
    {
        $service->runImageScan();

        Notification::make()
            ->title('Image Asset Scan Completed')
            ->body('DrutoGoti scanned public assets and generated optimization metrics.')
            ->success()
            ->send();
    }
}
