<?php

namespace App\Filament\Client\Resources\ClientListingResource\Pages;

use App\Filament\Client\Resources\ClientListingResource;
use Filament\Resources\Pages\ListRecords;

class ListClientListings extends ListRecords
{
    protected static string $resource = ClientListingResource::class;
}
