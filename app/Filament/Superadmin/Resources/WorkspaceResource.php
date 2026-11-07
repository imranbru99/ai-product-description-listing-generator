<?php

namespace App\Filament\Superadmin\Resources;

use App\Filament\Superadmin\Resources\WorkspaceResource\Pages;
use App\Models\Workspace;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WorkspaceResource extends Resource
{
    protected static ?string $model = Workspace::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationGroup = 'Platform Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true),
                Forms\Components\Select::make('type')
                    ->options([
                        'solo' => 'Solo Seller',
                        'dropshipper' => 'Dropshipper',
                        'brand' => 'Brand / Manufacturer',
                        'agency' => 'Marketplace Agency',
                        'enterprise' => 'Enterprise Content Team',
                        'wholesaler' => 'Wholesaler / Distributor',
                    ])
                    ->required(),
                Forms\Components\Select::make('plan_id')
                    ->relationship('plan', 'name'),
                Forms\Components\TextInput::make('credits_balance')
                    ->numeric()
                    ->default(100),
                Forms\Components\TextInput::make('currency')
                    ->default('USD')
                    ->maxLength(3),
                Forms\Components\TextInput::make('domain')
                    ->placeholder('listings.clientbrand.com'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('slug')->badge(),
                Tables\Columns\TextColumn::make('type')->badge()->color('gray'),
                Tables\Columns\TextColumn::make('plan.name')->sortable(),
                Tables\Columns\TextColumn::make('credits_balance')->sortable()->color('success'),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')->options([
                    'solo' => 'Solo Seller',
                    'dropshipper' => 'Dropshipper',
                    'brand' => 'Brand',
                    'agency' => 'Agency',
                    'enterprise' => 'Enterprise',
                ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWorkspaces::route('/'),
            'create' => Pages\CreateWorkspace::route('/create'),
            'edit' => Pages\EditWorkspace::route('/{record}/edit'),
        ];
    }
}
