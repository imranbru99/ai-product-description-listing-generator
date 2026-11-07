@extends('layouts.public')

@section('title', 'Marketplaces & Store Channels Directory')

@section('content')
<div class="py-16 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 text-center">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-4">Supported Marketplaces & Channels</h1>
        <p class="text-base text-gray-600 dark:text-gray-400 max-w-xl mx-auto">
            Autonomous compliance, character bounds, and formatting rules customized for each marketplace ecosystem.
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @php
            $channels = [
                ['code' => 'amazon', 'name' => 'Amazon Seller Central', 'tag' => 'Marketplace', 'desc' => 'Character limits (200 chars), 5 bullet points, search terms, and prohibited claims compliance.'],
                ['code' => 'shopify', 'name' => 'Shopify Storefronts', 'tag' => 'E-Commerce Platform', 'desc' => 'Rich HTML descriptions, automated handle slugs, collection tags, and SEO meta tags.'],
                ['code' => 'ebay', 'name' => 'eBay Global Stores', 'tag' => 'Marketplace', 'desc' => '80-character title optimization, item specifics, and HTML description templates.'],
                ['code' => 'walmart', 'name' => 'Walmart Marketplace', 'tag' => 'Enterprise Marketplace', 'desc' => 'Strict 100-character titles, key feature bullets, and category taxonomy validation.'],
                ['code' => 'etsy', 'name' => 'Etsy Handmade & Crafts', 'tag' => 'Specialty', 'desc' => '140-character titles, 13 search tags, materials attributes, and story copy.'],
                ['code' => 'daraz', 'name' => 'Daraz (Southeast Asia)', 'tag' => 'Regional Marketplace', 'desc' => 'Localized South Asian language copy (Bengali/Hindi), SKU specs, and express warranty copy.'],
            ];
        @endphp

        @foreach($channels as $c)
            <div class="p-6 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col justify-between hover:border-violet-500 transition">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-violet-100 text-violet-800 dark:bg-violet-950 dark:text-violet-300">
                        {{ $c['tag'] }}
                    </span>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mt-3 mb-2">{{ $c['name'] }}</h3>
                    <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">{{ $c['desc'] }}</p>
                </div>
                <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center text-xs">
                    <span class="text-emerald-500 font-semibold">✓ Rule Engine Active</span>
                    <a href="{{ route('marketplaces.detail', $c['code']) }}" class="font-bold text-violet-600 hover:underline">Rule Specs →</a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
