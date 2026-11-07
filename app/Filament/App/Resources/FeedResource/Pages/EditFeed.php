<?php

namespace App\Filament\App\Resources\FeedResource\Pages;

use App\Filament\App\Resources\FeedResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFeed extends EditRecord
{
    protected static string $resource = FeedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
