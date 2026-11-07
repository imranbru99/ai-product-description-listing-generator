<?php

namespace App\Filament\Partner\Resources\PartnerPayoutResource\Pages;

use App\Filament\Partner\Resources\PartnerPayoutResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPartnerPayouts extends ListRecords
{
    protected static string $resource = PartnerPayoutResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Request New Payout'),
        ];
    }
}
