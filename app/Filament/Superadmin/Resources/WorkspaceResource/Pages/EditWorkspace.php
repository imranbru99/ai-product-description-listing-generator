<?php

namespace App\Filament\Superadmin\Resources\WorkspaceResource\Pages;

use App\Filament\Superadmin\Resources\WorkspaceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWorkspace extends EditRecord
{
    protected static string $resource = WorkspaceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
