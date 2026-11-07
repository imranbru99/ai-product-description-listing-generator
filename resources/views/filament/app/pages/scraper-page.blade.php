<x-filament-panels::page>
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">Autonomous Product & Competitor Scraper</h3>
            <p class="text-sm text-gray-500 mb-4">
                Powered by <code class="text-xs bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 rounded">imrandevbd/laravel-ai-catalog</code>. Paste any competitor product, supplier catalog, or dropship page URL. Automatic SSRF defense protects private networks.
            </p>

            <form wire:submit="scrape" class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold uppercase text-gray-500 mb-1">Target Product URL</label>
                    <input
                        type="url"
                        wire:model="url"
                        placeholder="https://example.com/products/wireless-earbuds-pro"
                        required
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-violet-500 text-sm"
                    />
                    @error('url') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center justify-between pt-2">
                    <span class="text-xs text-gray-400">🛡️ SSRF protection active (Private IPs & Cloud metadata blocked)</span>
                    <x-filament::button type="submit" color="primary">
                        Extract & Import Product
                    </x-filament::button>
                </div>
            </form>
        </div>

        @if($scrapedResult)
            <div class="bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800 rounded-2xl p-6">
                <div class="flex items-center justify-between mb-3">
                    <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded-full text-xs font-bold uppercase">
                        Import Success
                    </span>
                    <a
                        href="{{ route('filament.app.resources.product-listings.create', [
                            'tenant' => \Filament\Facades\Filament::getTenant()->slug,
                            'product_id' => $scrapedResult['id']
                        ]) }}"
                        class="text-xs font-semibold text-emerald-700 hover:text-emerald-900 underline"
                    >
                        Generate Listing in AI Studio →
                    </a>
                </div>
                <h4 class="text-base font-bold text-gray-900 dark:text-gray-100">{{ $scrapedResult['name'] }}</h4>
                <div class="text-sm font-semibold text-emerald-600 mt-1">{{ $scrapedResult['currency'] }} {{ number_format($scrapedResult['price'], 2) }}</div>

                <div class="mt-4 pt-4 border-t border-emerald-200 dark:border-emerald-800/50">
                    <div class="text-xs font-semibold uppercase text-gray-500 mb-2">Extracted Specifications & Metadata:</div>
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        @foreach($scrapedResult['attributes'] as $k => $v)
                            <div class="bg-white/60 dark:bg-gray-900/60 p-2 rounded border border-emerald-100 dark:border-emerald-900/30">
                                <span class="text-gray-500">{{ $k }}:</span> <span class="font-medium text-gray-800 dark:text-gray-200">{{ is_array($v) ? json_encode($v) : $v }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page>
