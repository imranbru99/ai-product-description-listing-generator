<?php

namespace App\Filament\Superadmin\Resources;

use App\Filament\Superadmin\Resources\ModuleResource\Pages;
use App\Models\Module;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ModuleResource extends Resource
{
    protected static ?string $model = Module::class;
    protected static ?string $navigationIcon = 'heroicon-o-squares-plus';
    protected static ?string $navigationGroup = 'Platform Management';
    protected static ?string $navigationLabel = 'Module Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('key')->disabled(),
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('category')->disabled(),
                Forms\Components\Toggle::make('is_globally_enabled')->label('Globally Enabled Platform-Wide'),
                Forms\Components\Textarea::make('description')->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')->badge()->color('gray'),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('category')->badge()->color('primary'),
                Tables\Columns\IconColumn::make('is_core')->boolean()->label('Core (Required)'),
                Tables\Columns\ToggleColumn::make('is_globally_enabled')->label('Global Status'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')->options([
                    'core' => 'Core',
                    'catalog' => 'Catalog',
                    'ai' => 'AI Engine',
                    'marketplace' => 'Marketplace',
                    'workflow' => 'Workflow',
                    'business' => 'Business',
                    'platform' => 'Platform',
                ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListModules::route('/'),
            'edit' => Pages\EditModule::route('/{record}/edit'),
        ];
    }
}
