<?php

namespace App\Filament\App\Resources\BulkJobResource\Pages;

use App\Filament\App\Resources\BulkJobResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBulkJob extends ViewRecord
{
    protected static string $resource = BulkJobResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
