<?php

namespace App\Filament\Partner\Resources\PartnerPayoutResource\Pages;

use App\Filament\Partner\Resources\PartnerPayoutResource;
use App\Models\Affiliate;
use Filament\Resources\Pages\CreateRecord;

class CreatePartnerPayout extends CreateRecord
{
    protected static string $resource = PartnerPayoutResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $affiliate = Affiliate::firstOrCreate(
            ['user_id' => auth()->id()],
            ['referral_code' => strtoupper(\Illuminate\Support\Str::random(8)), 'commission_rate' => 20.00]
        );

        $data['affiliate_id'] = $affiliate->id;
        $data['status'] = 'pending';

        return $data;
    }
}
