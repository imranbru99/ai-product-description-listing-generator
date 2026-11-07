@extends('layouts.public')

@section('title', 'AI Product Description & Listing Generator: Bulk Marketplace Content')

@section('content')
<!-- Hero Section with Interactive Demo Generator -->
<section class="relative overflow-hidden pt-12 pb-20 bg-gradient-to-b from-violet-50/50 via-white to-gray-50 dark:from-gray-950 dark:via-gray-900 dark:to-gray-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-4xl mx-auto mb-12">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-violet-100 dark:bg-violet-950/60 border border-violet-200 dark:border-violet-800 text-violet-700 dark:text-violet-300 text-xs font-bold uppercase tracking-wider mb-6">
                <span class="w-2 h-2 rounded-full bg-violet-600 animate-ping"></span>
                Next-Gen Multi-Tenant Commerce Architecture · 48 Modular Engines
            </div>
            <h1 class="text-4xl sm:text-6xl font-black text-gray-900 dark:text-white tracking-tight leading-tight sm:leading-none mb-6">
                Scale Product Listings Across <span class="text-transparent bg-clip-text bg-gradient-to-r from-violet-600 to-indigo-600">Every Marketplace</span> with Autonomous AI
            </h1>
            <p class="text-lg sm:text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto leading-relaxed mb-8">
                Generate, optimize, translate, review, and bulk-publish high-converting listings for Amazon, eBay, Shopify, Walmart, Etsy, and Daraz. Turn on only the modules your business needs.
            </p>
            <div class="flex flex-wrap items-center justify-center gap-4">
                <a href="#interactive-generator" class="px-6 py-3.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white font-bold text-sm shadow-lg shadow-violet-500/25 transition">
                    ⚡ Try Live Generator Below
                </a>
                <a href="{{ route('demo') }}" class="px-6 py-3.5 rounded-xl bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700 text-gray-800 dark:text-gray-200 font-bold text-sm shadow-sm transition">
                    Explore Role Dashboards →
                </a>
            </div>
        </div>

        <!-- Interactive Live AI Listing Generator (Zero login required, powered by FakeListingGenerator) -->
        <div id="interactive-generator" class="max-w-5xl mx-auto bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden" x-data="{
            productName: 'AeroGlide Wireless Noise-Cancelling Headphones',
            marketplace: 'amazon',
            locale: 'en',
            loading: false,
            result: null,
            generate() {
                this.loading = true;
                fetch('{{ route('demo.sample') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        name: this.productName,
                        marketplace: this.marketplace,
                        locale: this.locale
                    })
                })
                .then(r => r.json())
                .then(data => {
                    this.result = data.data;
                    this.loading = false;
                })
                .catch(() => { this.loading = false; });
            }
        }" x-init="generate()">
            <div class="p-6 bg-gray-50 dark:bg-gray-900/60 border-b border-gray-200 dark:border-gray-700 flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                    <span class="text-sm font-bold text-gray-800 dark:text-gray-200">Interactive Studio Preview (Offline-Ready)</span>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <select x-model="marketplace" @change="generate()" class="px-3 py-1.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-xs font-semibold text-gray-700 dark:text-gray-300">
                        <option value="amazon">Amazon Listing</option>
                        <option value="ebay">eBay Listing</option>
                        <option value="shopify">Shopify Store</option>
                        <option value="walmart">Walmart</option>
                        <option value="etsy">Etsy</option>
                    </select>

                    <select x-model="locale" @change="generate()" class="px-3 py-1.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-xs font-semibold text-gray-700 dark:text-gray-300">
                        <option value="en">English (US)</option>
                        <option value="bn">Bengali (বাংলা)</option>
                        <option value="ar">Arabic (العربية - RTL)</option>
                        <option value="hi">Hindi (हिन्दी)</option>
                        <option value="es">Spanish (Español)</option>
                        <option value="de">German (Deutsch)</option>
                    </select>

                    <button @click="generate()" :disabled="loading" class="px-4 py-1.5 bg-violet-600 hover:bg-violet-700 text-white rounded-lg text-xs font-bold transition flex items-center gap-1.5">
                        <span x-show="loading" class="animate-spin">⏳</span>
                        <span>Regenerate</span>
                    </button>
                </div>
            </div>

            <!-- Input Bar -->
            <div class="p-6 border-b border-gray-100 dark:border-gray-700/60 flex items-center gap-4">
                <input
                    type="text"
                    x-model="productName"
                    @keydown.enter="generate()"
                    placeholder="Enter product title or seed keyword..."
                    class="flex-1 px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm font-medium focus:ring-2 focus:ring-violet-500"
                />
            </div>

            <!-- Output Preview -->
            <div class="p-6 sm:p-8" x-show="result" x-cloak>
                <div class="flex items-center gap-4 mb-4">
                    <span class="text-xs px-2.5 py-1 bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-400 font-bold rounded-full">
                        Quality Score: <span x-text="result.quality_score"></span>/100
                    </span>
                    <span class="text-xs px-2.5 py-1 bg-violet-100 text-violet-800 dark:bg-violet-950 dark:text-violet-400 font-bold rounded-full">
                        SEO Score: <span x-text="result.seo_score"></span>/100
                    </span>
                    <span class="text-xs px-2.5 py-1 bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-400 font-bold rounded-full">
                        Policy Status: <span x-text="result.compliance_status.toUpperCase()"></span>
                    </span>
                </div>

                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4" x-text="result.title"></h3>
                <p class="text-sm text-gray-600 dark:text-gray-300 mb-6 leading-relaxed" x-text="result.short_description"></p>

                <div class="mb-6">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-3">Marketplace Bullet Points</h4>
                    <ul class="space-y-2">
                        <template x-for="(bullet, i) in result.bullet_points" :key="i">
                            <li class="flex items-start gap-2.5 text-xs text-gray-700 dark:text-gray-300">
                                <span class="text-emerald-500 font-bold">✓</span>
                                <span x-text="bullet"></span>
                            </li>
                        </template>
                    </ul>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-gray-100 dark:border-gray-700/60">
                    <div>
                        <div class="text-xs font-semibold text-gray-500 uppercase mb-1">Search Keywords</div>
                        <div class="flex flex-wrap gap-1.5">
                            <template x-for="kw in result.search_terms" :key="kw">
                                <span class="px-2 py-0.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-[11px] rounded" x-text="kw"></span>
                            </template>
                        </div>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-gray-500 uppercase mb-1">2027 Digital Product Passport</div>
                        <div class="text-xs text-gray-600 dark:text-gray-400">
                            Repairability: <span class="font-bold text-gray-900 dark:text-white" x-text="result.dpp_data ? result.dpp_data.repairability_score : '8.8/10'"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Social Proof / Marketplace Ecosystem -->
<section class="py-12 border-y border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <p class="text-xs uppercase font-bold text-gray-400 tracking-wider mb-6">Built to connect and publish seamlessly across major commerce channels</p>
        <div class="flex flex-wrap items-center justify-center gap-8 text-sm font-bold text-gray-500 dark:text-gray-400">
            <span class="flex items-center gap-2">📦 Amazon Seller Central</span>
            <span class="flex items-center gap-2">🛍️ Shopify Stores</span>
            <span class="flex items-center gap-2">🏷️ eBay Multi-Store</span>
            <span class="flex items-center gap-2">🛒 Walmart Marketplace</span>
            <span class="flex items-center gap-2">🎨 Etsy Crafts</span>
            <span class="flex items-center gap-2">🌏 Daraz Southeast Asia</span>
            <span class="flex items-center gap-2">📊 Google Merchant Feeds</span>
        </div>
    </div>
</section>

<!-- Module Ecosystem Overview (48 Modules) -->
<section class="py-20 bg-gray-50 dark:bg-gray-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight mb-4">
                48 Production Modules. Total Freedom.
            </h2>
            <p class="text-base text-gray-600 dark:text-gray-400">
                A solo dropshipper turns on 3 modules. A global enterprise agency activates all 48. Zero code bloat. Disabling a module hides menus, widgets, and routes while preserving 100% of your historical data.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach(array_slice($modules, 0, 12) as $key => $mod)
                <div class="p-5 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 hover:border-violet-400 dark:hover:border-violet-600 transition shadow-sm">
                    <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-violet-50 text-violet-700 dark:bg-violet-950 dark:text-violet-300">
                        {{ $mod['category'] }}
                    </span>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white mt-2 mb-1">{{ $mod['name'] }}</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2">{{ $mod['description'] }}</p>
                    <a href="{{ route('features.detail', $key) }}" class="text-xs font-semibold text-violet-600 dark:text-violet-400 hover:underline mt-3 inline-block">
                        Module details →
                    </a>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-10">
            <a href="{{ route('features') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-violet-600 text-white font-bold text-sm shadow hover:bg-violet-700 transition">
                Explore All 48 Modules →
            </a>
        </div>
    </div>
</section>

<!-- Pricing Teaser -->
<section class="py-20 bg-white dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight mb-4">
            Transparent Pricing That Scales With Your Catalog
        </h2>
        <p class="text-base text-gray-600 dark:text-gray-400 max-w-xl mx-auto mb-12">
            No forced tiers or hidden AI token markups. Choose the monthly preset that fits, or purchase on-demand credit packs anytime.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto text-left">
            @foreach($plans->take(3) as $plan)
                <div class="p-8 rounded-3xl border {{ $plan->is_popular ? 'border-2 border-violet-500 shadow-xl' : 'border-gray-200 dark:border-gray-800 shadow-sm' }} bg-white dark:bg-gray-800 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $plan->name }}</h3>
                            @if($plan->is_popular)
                                <span class="px-2.5 py-0.5 bg-violet-100 text-violet-800 text-[10px] font-bold uppercase rounded-full">Popular</span>
                            @endif
                        </div>
                        <div class="text-3xl font-extrabold text-gray-900 dark:text-white mb-2">
                            ${{ number_format($plan->price_monthly, 0) }} <span class="text-xs font-normal text-gray-500">/ month</span>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-6">{{ $plan->description }}</p>

                        <ul class="space-y-3 text-xs text-gray-600 dark:text-gray-300">
                            <li class="flex items-center gap-2">✓ <strong>{{ number_format($plan->monthly_credits) }}</strong> AI Generation Credits/mo</li>
                            <li class="flex items-center gap-2">✓ Up to <strong>{{ number_format($plan->max_products) }}</strong> Active Catalog SKUs</li>
                            <li class="flex items-center gap-2">✓ <strong>{{ $plan->max_users }}</strong> Team Members</li>
                            <li class="flex items-center gap-2">✓ Unicode PDF Catalog Line Sheets</li>
                        </ul>
                    </div>

                    <a href="{{ route('pricing') }}" class="w-full mt-8 py-2.5 rounded-xl text-center text-xs font-bold {{ $plan->is_popular ? 'bg-violet-600 text-white hover:bg-violet-700' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-200' }} transition">
                        Select {{ $plan->name }}
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Frequently Asked Questions -->
<section class="py-20 bg-gray-50 dark:bg-gray-950 border-t border-gray-200 dark:border-gray-800">
    <div class="max-w-4xl mx-auto px-4">
        <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white text-center mb-12">Frequently Asked Questions</h2>
        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-900 p-6 rounded-2xl border border-gray-200 dark:border-gray-800">
                <h3 class="text-base font-bold text-gray-900 dark:text-white mb-2">Can I run this platform completely offline without third-party API keys?</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Yes. The system includes an autonomous, deterministic Fake AI driver that generates realistic, language-aware listings in English, Bengali, Arabic, Hindi, Spanish, German, French, and Japanese for demos and local evaluation without spending a cent.</p>
            </div>
            <div class="bg-white dark:bg-gray-900 p-6 rounded-2xl border border-gray-200 dark:border-gray-800">
                <h3 class="text-base font-bold text-gray-900 dark:text-white mb-2">What happens to my data when I turn off a module?</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Data is never deleted. Disabling a module dynamically hides menus, widgets, routes, and background jobs. When re-enabled, all historical listings, settings, and logs are immediately restored intact.</p>
            </div>
            <div class="bg-white dark:bg-gray-900 p-6 rounded-2xl border border-gray-200 dark:border-gray-800">
                <h3 class="text-base font-bold text-gray-900 dark:text-white mb-2">Does the PDF generator support complex Unicode scripts and Arabic RTL?</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Yes. Powered by <code class="text-xs bg-gray-100 dark:bg-gray-800 px-1.5 py-0.5 rounded">laravel-unicode-pdf</code>, all line sheets, invoices, and audit reports render native Bengali conjuncts, right-to-left Arabic shaping, and native numerals flawlessly.</p>
            </div>
        </div>
    </div>
</section>

<!-- Final CTA -->
<section class="py-20 bg-gradient-to-r from-violet-600 to-indigo-700 text-white text-center">
    <div class="max-w-4xl mx-auto px-4">
        <h2 class="text-3xl sm:text-4xl font-black mb-4">Start Generating E-Commerce Listings Today</h2>
        <p class="text-base text-violet-100 max-w-xl mx-auto mb-8">Test the full platform instantly using our pre-seeded role accounts or launch your own workspace.</p>
        <div class="flex flex-wrap items-center justify-center gap-4">
            <a href="{{ route('demo') }}" class="px-8 py-3.5 bg-white text-violet-700 hover:bg-violet-50 rounded-xl font-bold text-sm shadow-lg transition">
                Try Live Demo Sandbox
            </a>
            <a href="/app" class="px-8 py-3.5 bg-violet-800/80 hover:bg-violet-800 border border-white/20 text-white rounded-xl font-bold text-sm transition">
                Launch Workspace Panel →
            </a>
        </div>
    </div>
</section>
@endsection
