<?php

namespace App\Filament\App\Resources\FeedResource\Pages;

use App\Filament\App\Resources\FeedResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFeeds extends ListRecords
{
    protected static string $resource = FeedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
