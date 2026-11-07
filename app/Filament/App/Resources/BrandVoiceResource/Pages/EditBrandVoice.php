<?php

namespace App\Filament\App\Resources\BrandVoiceResource\Pages;

use App\Filament\App\Resources\BrandVoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBrandVoice extends EditRecord
{
    protected static string $resource = BrandVoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
