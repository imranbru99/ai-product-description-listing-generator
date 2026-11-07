<?php

namespace App\Filament\App\Resources\FeedResource\Pages;

use App\Filament\App\Resources\FeedResource;
use App\Services\Marketplace\FeedGeneratorService;
use Filament\Resources\Pages\CreateRecord;

class CreateFeed extends CreateRecord
{
    protected static string $resource = FeedResource::class;

    protected function afterCreate(): void
    {
        app(FeedGeneratorService::class)->generate($this->record);
    }
}
