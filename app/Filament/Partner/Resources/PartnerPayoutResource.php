<?php

namespace App\Filament\Partner\Resources;

use App\Filament\Partner\Resources\PartnerPayoutResource\Pages;
use App\Models\AffiliatePayout;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PartnerPayoutResource extends Resource
{
    protected static ?string $model = AffiliatePayout::class;
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Payout Requests';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('amount')->numeric()->prefix('$')->required(),
                Forms\Components\Select::make('payment_method')
                    ->options([
                        'paypal' => 'PayPal',
                        'bank_wire' => 'Direct Bank Wire (SWIFT)',
                        'wise' => 'Wise (TransferWise)',
                    ])
                    ->default('paypal')
                    ->required(),
                Forms\Components\Textarea::make('notes')->placeholder('PayPal email or account details...'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('amount')->money('USD')->sortable(),
                Tables\Columns\TextColumn::make('payment_method')->badge(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'approved' => 'info',
                        'rejected' => 'danger',
                        default => 'warning',
                    }),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPartnerPayouts::route('/'),
            'create' => Pages\CreatePartnerPayout::route('/create'),
        ];
    }
}
