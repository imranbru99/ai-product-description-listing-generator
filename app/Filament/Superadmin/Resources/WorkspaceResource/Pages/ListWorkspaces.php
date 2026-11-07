<?php

namespace App\Filament\Superadmin\Resources\WorkspaceResource\Pages;

use App\Filament\Superadmin\Resources\WorkspaceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWorkspaces extends ListRecords
{
    protected static string $resource = WorkspaceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
