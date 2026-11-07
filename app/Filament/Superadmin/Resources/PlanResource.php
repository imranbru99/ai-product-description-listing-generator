<?php

namespace App\Filament\Superadmin\Resources;

use App\Filament\Superadmin\Resources\PlanResource\Pages;
use App\Models\Plan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PlanResource extends Resource
{
    protected static ?string $model = Plan::class;
    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationGroup = 'Platform Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true),
                Forms\Components\Textarea::make('description'),
                Forms\Components\TextInput::make('price_monthly')->numeric()->prefix('$')->required(),
                Forms\Components\TextInput::make('price_yearly')->numeric()->prefix('$')->required(),
                Forms\Components\TextInput::make('monthly_credits')->numeric()->default(100),
                Forms\Components\TextInput::make('max_products')->numeric()->default(50),
                Forms\Components\TextInput::make('max_users')->numeric()->default(2),
                Forms\Components\Toggle::make('is_active')->default(true),
                Forms\Components\Toggle::make('is_popular')->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('price_monthly')->money('USD')->sortable(),
                Tables\Columns\TextColumn::make('price_yearly')->money('USD')->sortable(),
                Tables\Columns\TextColumn::make('monthly_credits')->sortable(),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\IconColumn::make('is_popular')->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPlans::route('/'),
            'create' => Pages\CreatePlan::route('/create'),
            'edit' => Pages\EditPlan::route('/{record}/edit'),
        ];
    }
}
