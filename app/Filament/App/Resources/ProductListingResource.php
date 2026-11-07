<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\ProductListingResource\Pages;
use App\Models\Product;
use App\Models\ProductListing;
use App\Services\Ai\AiGenerationService;
use App\Services\Pdf\UnicodePdfService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductListingResource extends Resource
{
    protected static ?string $model = ProductListing::class;
    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?string $navigationGroup = 'AI Studio & Content';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'AI Listing Studio';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make([
                    Forms\Components\Section::make('Target Platform & Market')
                        ->schema([
                            Forms\Components\Select::make('product_id')
                                ->relationship('product', 'name')
                                ->required()
                                ->searchable()
                                ->default(request()->query('product_id')),
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
                                    'en' => 'English (US/UK)',
                                    'bn' => 'Bengali (বাংলা)',
                                    'ar' => 'Arabic (العربية - RTL)',
                                    'hi' => 'Hindi (हिन्दी)',
                                    'es' => 'Spanish (Español)',
                                    'fr' => 'French (Français)',
                                    'de' => 'German (Deutsch)',
                                    'ja' => 'Japanese (日本語)',
                                    'zh' => 'Chinese (中文)',
                                ])
                                ->default('en')
                                ->required(),
                            Forms\Components\Select::make('status')
                                ->options([
                                    'draft' => 'Draft',
                                    'in_review' => 'In Review',
                                    'approved' => 'Approved',
                                    'published' => 'Published',
                                    'rejected' => 'Rejected',
                                ])
                                ->default('draft')
                                ->required(),
                        ])->columns(2),

                    Forms\Components\Section::make('Generated Listing Content')
                        ->schema([
                            Forms\Components\TextInput::make('title')
                                ->label('Optimized Title')
                                ->required()
                                ->columnSpanFull(),
                            Forms\Components\Textarea::make('short_description')
                                ->label('PDP Short Summary')
                                ->rows(3)
                                ->columnSpanFull(),
                            Forms\Components\RichEditor::make('long_description')
                                ->label('Persuasive Long Description')
                                ->columnSpanFull(),
                            Forms\Components\TagsInput::make('bullet_points')
                                ->label('Key Features / Bullet Points (5 Recommended)')
                                ->placeholder('Add feature bullet point...')
                                ->columnSpanFull(),
                            Forms\Components\TagsInput::make('search_terms')
                                ->label('Backend Keywords / Search Terms')
                                ->columnSpanFull(),
                        ]),

                    Forms\Components\Section::make('Next-Gen Commerce (DPP & Social Video Scripts)')
                        ->collapsed()
                        ->schema([
                            Forms\Components\KeyValue::make('dpp_data')
                                ->label('Digital Product Passport (EU Ecodesign)')
                                ->keyLabel('Metric')
                                ->valueLabel('Value'),
                            Forms\Components\KeyValue::make('social_copy')
                                ->label('Social Commerce Storyboard (TikTok Shop / Reels)')
                                ->keyLabel('Segment')
                                ->valueLabel('Script / Visuals'),
                        ]),
                ])->columnSpan(['lg' => 2]),

                Forms\Components\Group::make([
                    Forms\Components\Section::make('Quality & Compliance Scores')
                        ->schema([
                            Forms\Components\Placeholder::make('seo_score')
                                ->label('SEO Score')
                                ->content(fn (?ProductListing $record): string => ($record?->seo_score ?? 95) . ' / 100'),
                            Forms\Components\Placeholder::make('quality_score')
                                ->label('Quality Score')
                                ->content(fn (?ProductListing $record): string => ($record?->quality_score ?? 98) . ' / 100'),
                            Forms\Components\Placeholder::make('compliance_status')
                                ->label('Marketplace Policy')
                                ->content(fn (?ProductListing $record): string => strtoupper($record?->compliance_status ?? 'PASSED')),
                            Forms\Components\Placeholder::make('version')
                                ->label('Revision Number')
                                ->content(fn (?ProductListing $record): string => 'v' . ($record?->version ?? 1)),
                        ]),

                    Forms\Components\Section::make('Workflow Governance')
                        ->schema([
                            Forms\Components\Select::make('assigned_to')
                                ->relationship('assignedTo', 'name')
                                ->searchable(),
                            Forms\Components\Select::make('approved_by')
                                ->relationship('approvedBy', 'name')
                                ->searchable(),
                        ]),
                ])->columnSpan(['lg' => 1]),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')->label('Product')->searchable()->sortable()->limit(25),
                Tables\Columns\TextColumn::make('marketplace')->badge()->color('primary'),
                Tables\Columns\TextColumn::make('locale')->badge()->color('info'),
                Tables\Columns\TextColumn::make('title')->searchable()->limit(35),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'approved' => 'info',
                        'in_review' => 'warning',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('seo_score')->label('SEO')->sortable()->badge()->color('success'),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('marketplace')->options([
                    'amazon' => 'Amazon',
                    'ebay' => 'eBay',
                    'shopify' => 'Shopify',
                    'walmart' => 'Walmart',
                    'etsy' => 'Etsy',
                ]),
                Tables\Filters\SelectFilter::make('status')->options([
                    'draft' => 'Draft',
                    'in_review' => 'In Review',
                    'approved' => 'Approved',
                    'published' => 'Published',
                ]),
            ])
            ->actions([
                Tables\Actions\Action::make('regenerate')
                    ->label('AI Rewrite')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->action(function (ProductListing $record, AiGenerationService $service) {
                        $newListing = $service->generateForProduct($record->product, [
                            'marketplace' => $record->marketplace,
                            'locale' => $record->locale,
                        ]);

                        $record->update([
                            'title' => $newListing->title,
                            'short_description' => $newListing->short_description,
                            'long_description' => $newListing->long_description,
                            'bullet_points' => $newListing->bullet_points,
                            'search_terms' => $newListing->search_terms,
                            'version' => $record->version + 1,
                        ]);

                        $record->snapshotVersion("AI Rewrite");

                        Notification::make()
                            ->title('Listing Regenerated')
                            ->body("Listing refreshed to version v{$record->version}")
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('download_pdf')
                    ->label('PDF Report')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('info')
                    ->action(function (ProductListing $record, UnicodePdfService $pdfService) {
                        $path = $pdfService->generateAuditReportPdf($record, $record->locale);
                        return response()->download(storage_path('app/public/' . $path));
                    }),

                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductListings::route('/'),
            'create' => Pages\CreateProductListing::route('/create'),
            'edit' => Pages\EditProductListing::route('/{record}/edit'),
        ];
    }
}
