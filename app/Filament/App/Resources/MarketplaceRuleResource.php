<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\MarketplaceRuleResource\Pages;
use App\Models\MarketplaceRule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MarketplaceRuleResource extends Resource
{
    protected static ?string $model = MarketplaceRule::class;
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'Marketplaces & Channels';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('marketplace_code')->required()->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('marketplace_name')->required(),
                Forms\Components\TextInput::make('title_max_length')->numeric()->default(200)->required(),
                Forms\Components\TextInput::make('bullet_max_count')->numeric()->default(5)->required(),
                Forms\Components\TextInput::make('bullet_max_length')->numeric()->default(500)->required(),
                Forms\Components\TextInput::make('desc_max_length')->numeric()->default(2000)->required(),
                Forms\Components\TagsInput::make('forbidden_terms')->label('Prohibited / Banned Words & Claims'),
                Forms\Components\Toggle::make('is_active')->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('marketplace_code')->badge()->color('primary'),
                Tables\Columns\TextColumn::make('marketplace_name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('title_max_length')->label('Max Title'),
                Tables\Columns\TextColumn::make('bullet_max_count')->label('Max Bullets'),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMarketplaceRules::route('/'),
            'create' => Pages\CreateMarketplaceRule::route('/create'),
            'edit' => Pages\EditMarketplaceRule::route('/{record}/edit'),
        ];
    }
}
