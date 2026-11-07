<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\TemplateResource\Pages;
use App\Models\Template;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TemplateResource extends Resource
{
    protected static ?string $model = Template::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'AI Studio & Content';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true),
                Forms\Components\Select::make('marketplace')
                    ->options([
                        'all' => 'Universal',
                        'amazon' => 'Amazon',
                        'ebay' => 'eBay',
                        'shopify' => 'Shopify',
                        'etsy' => 'Etsy',
                    ])
                    ->default('all')
                    ->required(),
                Forms\Components\Select::make('category')
                    ->options([
                        'general' => 'General Merchandise',
                        'electronics' => 'Consumer Electronics',
                        'fashion' => 'Apparel & Fashion',
                        'home' => 'Home & Living',
                        'beauty' => 'Health & Beauty',
                    ])
                    ->default('general')
                    ->required(),
                Forms\Components\Textarea::make('system_prompt')
                    ->label('AI System Persona Prompt')
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('user_prompt_template')
                    ->label('User Prompt Template (Use {product_name}, {brand}, {specs})')
                    ->rows(5)
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_active')->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('marketplace')->badge()->color('primary'),
                Tables\Columns\TextColumn::make('category')->badge()->color('gray'),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\TextColumn::make('version')->badge(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTemplates::route('/'),
            'create' => Pages\CreateTemplate::route('/create'),
            'edit' => Pages\EditTemplate::route('/{record}/edit'),
        ];
    }
}
