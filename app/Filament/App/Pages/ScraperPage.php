<?php

namespace App\Filament\App\Pages;

use App\Services\Scraper\UrlScraperService;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ScraperPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';
    protected static ?string $navigationGroup = 'Catalog Management';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationLabel = 'Competitor & URL Scraper';
    protected static ?string $title = 'Import from URL & Competitor Scraper';
    protected static string $view = 'filament.app.pages.scraper-page';

    public string $url = '';
    public bool $autoGenerateListing = true;
    public ?array $scrapedResult = null;

    public function scrape(UrlScraperService $scraper): void
    {
        $this->validate([
            'url' => ['required', 'url'],
        ]);

        try {
            $workspace = Filament::getTenant();
            $product = $scraper->importFromUrl($workspace, $this->url);

            $this->scrapedResult = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'currency' => $product->currency,
                'attributes' => $product->attributes ?? [],
                'url' => $this->url,
            ];

            Notification::make()
                ->title('Product Scraped & Saved')
                ->body("Successfully imported '{$product->name}' into catalog.")
                ->success()
                ->send();
        } catch (\InvalidArgumentException $e) {
            Notification::make()
                ->title('SSRF Blocked')
                ->body($e->getMessage())
                ->danger()
                ->send();
        } catch (\Throwable $e) {
            Notification::make()
                ->title('Scraper Error')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
