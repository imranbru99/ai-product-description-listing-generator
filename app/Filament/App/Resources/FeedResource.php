<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\FeedResource\Pages;
use App\Models\Feed;
use App\Services\Marketplace\FeedGeneratorService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class FeedResource extends Resource
{
    protected static ?string $model = Feed::class;
    protected static ?string $navigationIcon = 'heroicon-o-rss';
    protected static ?string $navigationGroup = 'Marketplaces & Channels';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true),
                Forms\Components\Select::make('type')
                    ->options([
                        'google_merchant' => 'Google Merchant Center (XML RSS 2.0)',
                        'meta_catalog' => 'Meta Commerce Manager / Facebook (CSV)',
                        'generic_csv' => 'Generic Multi-Channel Feed (CSV)',
                    ])
                    ->default('google_merchant')
                    ->required(),
                Forms\Components\Hidden::make('token')->default(fn () => Str::random(32)),
                Forms\Components\Toggle::make('is_active')->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('type')->badge()->color('primary'),
                Tables\Columns\TextColumn::make('item_count')->label('Items Count')->badge()->color('success'),
                Tables\Columns\TextColumn::make('regenerated_at')->dateTime()->sortable(),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
            ])
            ->actions([
                Tables\Actions\Action::make('regenerate')
                    ->label('Regenerate Feed')
                    ->icon('heroicon-o-arrow-path')
                    ->color('primary')
                    ->action(function (Feed $record, FeedGeneratorService $service) {
                        $service->generate($record);
                        Notification::make()
                            ->title('Feed Regenerated')
                            ->body("Updated feed containing {$record->item_count} catalog items.")
                            ->success()
                            ->send();
                    }),
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFeeds::route('/'),
            'create' => Pages\CreateFeed::route('/create'),
            'edit' => Pages\EditFeed::route('/{record}/edit'),
        ];
    }
}
