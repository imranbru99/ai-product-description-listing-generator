<?php

namespace App\Filament\App\Resources\MarketplaceRuleResource\Pages;

use App\Filament\App\Resources\MarketplaceRuleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMarketplaceRule extends EditRecord
{
    protected static string $resource = MarketplaceRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
