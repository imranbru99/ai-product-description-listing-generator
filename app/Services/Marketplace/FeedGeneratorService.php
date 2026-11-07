<?php

namespace App\Services\Marketplace;

use App\Models\Feed;
use Illuminate\Support\Facades\Storage;

class FeedGeneratorService
{
    /**
     * Generate feed file according to type (Google Merchant, Meta Catalog, Generic XML/CSV).
     */
    public function generate(Feed $feed): string
    {
        $products = $feed->workspace->products()->with(['brand', 'category', 'listings'])->take(500)->get();

        $content = match ($feed->type) {
            'google_merchant' => $this->generateGoogleMerchantXml($feed, $products),
            'meta_catalog' => $this->generateMetaCatalogCsv($feed, $products),
            'generic_csv' => $this->generateGenericCsv($feed, $products),
            default => $this->generateGoogleMerchantXml($feed, $products),
        };

        $extension = str_contains($feed->type, 'csv') ? 'csv' : 'xml';
        $filename = "feeds/{$feed->slug}-{$feed->token}.{$extension}";

        Storage::disk('public')->put($filename, $content);

        $feed->update([
            'file_path' => $filename,
            'item_count' => $products->count(),
            'regenerated_at' => now(),
        ]);

        return $filename;
    }

    protected function generateGoogleMerchantXml(Feed $feed, $products): string
    {
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><rss version="2.0" xmlns:g="http://base.google.com/ns/1.0"/>');
        $channel = $xml->addChild('channel');
        $channel->addChild('title', $feed->workspace->name . ' - Product Feed');
        $channel->addChild('link', config('app.url'));
        $channel->addChild('description', 'Autonomous AI Generated E-Commerce Listing Feed');

        foreach ($products as $p) {
            $listing = $p->listings->first();
            $item = $channel->addChild('item');
            $item->addChild('g:id', (string) $p->id, 'http://base.google.com/ns/1.0');
            $item->addChild('g:title', htmlspecialchars($listing->title ?? $p->name), 'http://base.google.com/ns/1.0');
            $item->addChild('g:description', htmlspecialchars($listing->short_description ?? 'High quality product'), 'http://base.google.com/ns/1.0');
            $item->addChild('g:link', config('app.url') . "/products/{$p->slug}", 'http://base.google.com/ns/1.0');
            $item->addChild('g:price', "{$p->price} {$p->currency}", 'http://base.google.com/ns/1.0');
            $item->addChild('g:availability', 'in_stock', 'http://base.google.com/ns/1.0');
            $item->addChild('g:brand', htmlspecialchars($p->brand?->name ?? 'Apex'), 'http://base.google.com/ns/1.0');
        }

        return $xml->asXML();
    }

    protected function generateMetaCatalogCsv(Feed $feed, $products): string
    {
        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, ['id', 'title', 'description', 'availability', 'condition', 'price', 'link', 'image_link', 'brand']);

        foreach ($products as $p) {
            $listing = $p->listings->first();
            fputcsv($handle, [
                $p->id,
                $listing->title ?? $p->name,
                $listing->short_description ?? 'Quality item',
                'in stock',
                'new',
                "{$p->price} {$p->currency}",
                config('app.url') . "/products/{$p->slug}",
                config('app.url') . "/images/placeholder.jpg",
                $p->brand?->name ?? 'Apex',
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return $csv;
    }

    protected function generateGenericCsv(Feed $feed, $products): string
    {
        return $this->generateMetaCatalogCsv($feed, $products);
    }
}
