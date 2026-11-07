<?php

namespace App\Filament\Client\Resources;

use App\Filament\Client\Resources\ClientListingResource\Pages;
use App\Models\Comment;
use App\Models\ProductListing;
use App\Services\Pdf\UnicodePdfService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ClientListingResource extends Resource
{
    protected static ?string $model = ProductListing::class;
    protected static ?string $navigationIcon = 'heroicon-o-check-badge';
    protected static ?string $navigationLabel = 'Listings for Approval';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')->disabled()->columnSpanFull(),
                Forms\Components\Textarea::make('short_description')->disabled()->columnSpanFull(),
                Forms\Components\RichEditor::make('long_description')->disabled()->columnSpanFull(),
                Forms\Components\TagsInput::make('bullet_points')->disabled()->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')->label('Product')->sortable(),
                Tables\Columns\TextColumn::make('marketplace')->badge()->color('primary'),
                Tables\Columns\TextColumn::make('locale')->badge()->color('info'),
                Tables\Columns\TextColumn::make('title')->limit(30),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'published' => 'success',
                        'rejected' => 'danger',
                        default => 'warning',
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Approve Listing')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (ProductListing $record) {
                        $record->update([
                            'status' => 'approved',
                            'approved_by' => auth()->id(),
                            'approved_at' => now(),
                        ]);

                        Notification::make()
                            ->title('Listing Approved')
                            ->body('This listing has been approved and moved to the publishing queue.')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('request_change')
                    ->label('Request Edits')
                    ->icon('heroicon-o-chat-bubble-bottom-center-text')
                    ->color('warning')
                    ->form([
                        Forms\Components\Textarea::make('comment')
                            ->label('Feedback / Requested Adjustments')
                            ->required(),
                    ])
                    ->action(function (ProductListing $record, array $data) {
                        $record->update(['status' => 'in_review']);

                        Comment::create([
                            'workspace_id' => $record->workspace_id,
                            'user_id' => auth()->id(),
                            'commentable_type' => ProductListing::class,
                            'commentable_id' => $record->id,
                            'content' => "[Client Revision Request]: " . $data['comment'],
                        ]);

                        Notification::make()
                            ->title('Change Request Sent')
                            ->body('Your revision feedback has been sent to the content team.')
                            ->warning()
                            ->send();
                    }),

                Tables\Actions\Action::make('download_pdf')
                    ->label('Download PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('info')
                    ->action(function (ProductListing $record, UnicodePdfService $pdfService) {
                        $path = $pdfService->generateAuditReportPdf($record, $record->locale);
                        return response()->download(storage_path('app/public/' . $path));
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClientListings::route('/'),
            'view' => Pages\ViewClientListing::route('/{record}'),
        ];
    }
}
