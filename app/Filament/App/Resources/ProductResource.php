<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationGroup = 'Catalog Management';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('General Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('sku')
                            ->label('SKU')
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('barcode')
                            ->label('Barcode / GTIN'),
                        Forms\Components\Select::make('category_id')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('brand_id')
                            ->relationship('brand', 'name')
                            ->searchable()
                            ->preload(),
                    ])->columns(2),

                Forms\Components\Section::make('Pricing & Status')
                    ->schema([
                        Forms\Components\TextInput::make('price')
                            ->numeric()
                            ->prefix('$')
                            ->required(),
                        Forms\Components\TextInput::make('cost_price')
                            ->numeric()
                            ->prefix('$'),
                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'active' => 'Active',
                                'archived' => 'Archived',
                            ])
                            ->default('active')
                            ->required(),
                    ])->columns(3),

                Forms\Components\Section::make('Specifications & Tags')
                    ->schema([
                        Forms\Components\TagsInput::make('tags')
                            ->placeholder('Add keyword tag...'),
                        Forms\Components\KeyValue::make('attributes')
                            ->label('Product Attributes (Specs, Materials, Dimensions)')
                            ->keyLabel('Attribute')
                            ->valueLabel('Value'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable()->limit(35),
                Tables\Columns\TextColumn::make('sku')->badge()->color('gray'),
                Tables\Columns\TextColumn::make('category.name')->sortable(),
                Tables\Columns\TextColumn::make('brand.name')->sortable(),
                Tables\Columns\TextColumn::make('price')->money('USD')->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'draft' => 'warning',
                        'archived' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('listings_count')->counts('listings')->label('Listings')->badge(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'active' => 'Active',
                    'draft' => 'Draft',
                    'archived' => 'Archived',
                ]),
            ])
            ->actions([
                Tables\Actions\Action::make('generate_ai')
                    ->label('AI Studio')
                    ->icon('heroicon-o-sparkles')
                    ->color('primary')
                    ->url(fn (Product $record): string => route('filament.app.resources.product-listings.create', [
                        'tenant' => \Filament\Facades\Filament::getTenant()->slug,
                        'product_id' => $record->id,
                    ])),
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
