<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\BrandVoiceResource\Pages;
use App\Models\BrandVoice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BrandVoiceResource extends Resource
{
    protected static ?string $model = BrandVoice::class;
    protected static ?string $navigationIcon = 'heroicon-o-speaker-wave';
    protected static ?string $navigationGroup = 'AI Studio & Content';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\Select::make('brand_id')->relationship('brand', 'name'),
                Forms\Components\Select::make('tone')
                    ->options([
                        'professional' => 'Professional & Authoritative',
                        'conversational' => 'Conversational & Friendly',
                        'luxurious' => 'Luxurious & High-End',
                        'witty' => 'Witty & Playful',
                        'urgent' => 'Direct-Response / High Urgency',
                        'technical' => 'Technical & Spec-Heavy',
                        'bold' => 'Bold & Disruptive',
                    ])
                    ->default('professional')
                    ->required(),
                Forms\Components\Select::make('reading_level')
                    ->options([
                        'simple' => 'Grade 6 (Simple & Clear)',
                        'standard' => 'Grade 8-10 (Standard Consumer)',
                        'advanced' => 'Grade 12+ (Technical / B2B)',
                    ])
                    ->default('standard'),
                Forms\Components\TagsInput::make('banned_words')
                    ->label('Banned / Forbidden Words')
                    ->placeholder('Add prohibited word...'),
                Forms\Components\TagsInput::make('required_words')
                    ->label('Mandatory Phrases & Brand Hashtags')
                    ->placeholder('Add required phrase...'),
                Forms\Components\Textarea::make('style_examples')
                    ->label('Exemplar Tone Snippets (Few-Shot Context)')
                    ->rows(4)
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_default')->label('Set as Default Workspace Voice'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('tone')->badge()->color('primary'),
                Tables\Columns\TextColumn::make('reading_level')->badge()->color('gray'),
                Tables\Columns\IconColumn::make('is_default')->boolean(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBrandVoices::route('/'),
            'create' => Pages\CreateBrandVoice::route('/create'),
            'edit' => Pages\EditBrandVoice::route('/{record}/edit'),
        ];
    }
}
