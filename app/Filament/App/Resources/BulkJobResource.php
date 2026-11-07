<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\BulkJobResource\Pages;
use App\Jobs\ProcessBulkJob;
use App\Models\BulkJob;
use App\Models\BulkJobItem;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BulkJobResource extends Resource
{
    protected static ?string $model = BulkJob::class;
    protected static ?string $navigationIcon = 'heroicon-o-queue-list';
    protected static ?string $navigationGroup = 'AI Studio & Content';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Bulk Generation Engine';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Bulk Generation Setup')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->default('Catalog Generation Batch #' . date('Ymd-Hi'))
                            ->maxLength(255),
                        Forms\Components\Select::make('template_id')
                            ->relationship('template', 'name')
                            ->placeholder('Default E-Commerce Template'),
                        Forms\Components\Select::make('marketplace')
                            ->options([
                                'amazon' => 'Amazon',
                                'ebay' => 'eBay',
                                'shopify' => 'Shopify',
                                'walmart' => 'Walmart',
                                'etsy' => 'Etsy',
                                'daraz' => 'Daraz',
                                'generic' => 'Generic Multi-Channel',
                            ])
                            ->default('amazon')
                            ->required(),
                        Forms\Components\Select::make('locale')
                            ->options([
                                'en' => 'English (en)',
                                'bn' => 'Bengali (bn)',
                                'ar' => 'Arabic (ar)',
                                'hi' => 'Hindi (hi)',
                                'es' => 'Spanish (es)',
                                'fr' => 'French (fr)',
                                'de' => 'German (de)',
                                'ja' => 'Japanese (ja)',
                                'zh' => 'Chinese (zh)',
                            ])
                            ->default('en')
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Product Selection')
                    ->schema([
                        Forms\Components\Select::make('products')
                            ->label('Select Products for Generation')
                            ->multiple()
                            ->options(fn () => Product::forWorkspace(\Filament\Facades\Filament::getTenant()->id)->pluck('name', 'id'))
                            ->searchable()
                            ->helperText('Leave empty to process all products currently in workspace catalog.'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('marketplace')->badge()->color('primary'),
                Tables\Columns\TextColumn::make('locale')->badge()->color('info'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'processing' => 'warning',
                        'failed' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('progress')
                    ->label('Progress')
                    ->state(fn (BulkJob $record): string => "{$record->processed_items} / {$record->total_items} ({$record->progress_percent}%)")
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('actual_cost')
                    ->label('Cost')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('retry')
                    ->label('Run / Retry')
                    ->icon('heroicon-o-play')
                    ->color('success')
                    ->visible(fn (BulkJob $record): bool => in_array($record->status, ['pending', 'failed', 'paused']))
                    ->action(function (BulkJob $record) {
                        ProcessBulkJob::dispatch($record);
                        Notification::make()
                            ->title('Batch Queued')
                            ->body('Bulk generation worker dispatched to queue.')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBulkJobs::route('/'),
            'create' => Pages\CreateBulkJob::route('/create'),
            'view' => Pages\ViewBulkJob::route('/{record}'),
        ];
    }
}
