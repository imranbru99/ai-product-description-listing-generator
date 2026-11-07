<?php

namespace App\Filament\Partner\Resources;

use App\Filament\Partner\Resources\PartnerReferralResource\Pages;
use App\Models\AffiliateReferral;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PartnerReferralResource extends Resource
{
    protected static ?string $model = AffiliateReferral::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Referred Workspaces';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('workspace.name')->label('Client Business')->sortable(),
                Tables\Columns\TextColumn::make('workspace.plan.name')->label('Subscription Plan')->badge(),
                Tables\Columns\TextColumn::make('commission_amount')->label('Earned Commission')->money('USD')->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'approved' => 'info',
                        default => 'warning',
                    }),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPartnerReferrals::route('/'),
        ];
    }
}
