<?php

namespace App\Filament\App\Resources\BulkJobResource\Pages;

use App\Filament\App\Resources\BulkJobResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBulkJobs extends ListRecords
{
    protected static string $resource = BulkJobResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('New Bulk Batch'),
        ];
    }
}
