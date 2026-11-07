<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('theme') === 'dark' }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name')) | Next-Gen E-Commerce SaaS</title>
    <meta name="description" content="@yield('meta_description', 'The world\'s most complete AI platform for generating, optimizing, translating, and bulk-publishing product listings across every major marketplace.')">

    <!-- OpenGraph & Twitter Cards -->
    <meta property="og:title" content="@yield('title', config('app.name'))">
    <meta property="og:description" content="@yield('meta_description', 'Autonomous Bulk Marketplace Content Engine')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="twitter:card" content="summary_large_image">

    <!-- Anti-Flash Theme Script -->
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f5f3ff',
                            100: '#ede9fe',
                            200: '#ddd6fe',
                            500: '#8b5cf6',
                            600: '#7c3aed',
                            700: '#6d28d9',
                            800: '#5b21b6',
                            900: '#4c1d95',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Structured JSON-LD -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "SoftwareApplication",
      "name": "{{ config('app.name') }}",
      "applicationCategory": "BusinessApplication",
      "operatingSystem": "All",
      "offers": {
        "@type": "Offer",
        "price": "0.00",
        "priceCurrency": "USD"
      },
      "author": {
        "@type": "Person",
        "name": "Imran Ahmed",
        "email": "me@imrandev.bd",
        "url": "https://imrandev.bd"
      }
    }
    </script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100 font-sans antialiased selection:bg-violet-500 selection:text-white min-h-screen flex flex-col justify-between">

    <!-- Public Navigation Bar -->
    <header class="sticky top-0 z-50 bg-white/90 dark:bg-gray-900/90 backdrop-blur-md border-b border-gray-200 dark:border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand Logo & Name -->
                <div class="flex items-center gap-8">
                    <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-violet-600 to-indigo-600 flex items-center justify-center text-white font-black shadow-md shadow-violet-500/20 text-lg">
                            AI
                        </div>
                        <span class="font-extrabold text-base tracking-tight text-gray-900 dark:text-white hidden sm:inline">
                            {{ config('app.name') }}
                        </span>
                    </a>

                    <!-- Desktop Nav Links -->
                    <nav class="hidden lg:flex items-center space-x-6 text-sm font-medium text-gray-600 dark:text-gray-300">
                        <a href="{{ route('features') }}" class="hover:text-violet-600 dark:hover:text-violet-400 transition">Features</a>
                        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                            <button @click="open = !open" class="flex items-center gap-1 hover:text-violet-600 dark:hover:text-violet-400 transition">
                                Solutions
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div x-show="open" x-cloak class="absolute left-0 mt-2 w-56 rounded-xl bg-white dark:bg-gray-800 shadow-xl border border-gray-100 dark:border-gray-700 py-2 z-50">
                                <a href="{{ route('solutions', 'solo') }}" class="block px-4 py-2 text-xs hover:bg-violet-50 dark:hover:bg-gray-700/60 font-semibold text-gray-800 dark:text-gray-200">Solo Sellers</a>
                                <a href="{{ route('solutions', 'dropshipper') }}" class="block px-4 py-2 text-xs hover:bg-violet-50 dark:hover:bg-gray-700/60 font-semibold text-gray-800 dark:text-gray-200">Dropshippers</a>
                                <a href="{{ route('solutions', 'brand') }}" class="block px-4 py-2 text-xs hover:bg-violet-50 dark:hover:bg-gray-700/60 font-semibold text-gray-800 dark:text-gray-200">Brands & Manufacturers</a>
                                <a href="{{ route('solutions', 'agency') }}" class="block px-4 py-2 text-xs hover:bg-violet-50 dark:hover:bg-gray-700/60 font-semibold text-gray-800 dark:text-gray-200">Marketplace Agencies</a>
                                <a href="{{ route('solutions', 'enterprise') }}" class="block px-4 py-2 text-xs hover:bg-violet-50 dark:hover:bg-gray-700/60 font-semibold text-gray-800 dark:text-gray-200">Enterprise Content Teams</a>
                                <a href="{{ route('solutions', 'wholesaler') }}" class="block px-4 py-2 text-xs hover:bg-violet-50 dark:hover:bg-gray-700/60 font-semibold text-gray-800 dark:text-gray-200">Wholesalers & Distributors</a>
                            </div>
                        </div>
                        <a href="{{ route('marketplaces') }}" class="hover:text-violet-600 dark:hover:text-violet-400 transition">Marketplaces</a>
                        <a href="{{ route('pricing') }}" class="hover:text-violet-600 dark:hover:text-violet-400 transition">Pricing</a>
                        <a href="{{ route('templates.gallery') }}" class="hover:text-violet-600 dark:hover:text-violet-400 transition">Templates</a>
                        <a href="{{ route('demo') }}" class="hover:text-violet-600 dark:hover:text-violet-400 transition flex items-center gap-1.5 font-bold text-violet-600 dark:text-violet-400">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            Live Demo
                        </a>
                        <a href="{{ route('blog') }}" class="hover:text-violet-600 dark:hover:text-violet-400 transition">Blog</a>
                    </nav>
                </div>

                <!-- Right Action Controls -->
                <div class="flex items-center gap-3">
                    <!-- Dark/Light Mode Switcher -->
                    <button
                        type="button"
                        @click="darkMode = !darkMode; localStorage.setItem('theme', darkMode ? 'dark' : 'light')"
                        class="p-2 rounded-lg text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition"
                        title="Toggle dark/light theme"
                    >
                        <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                        <svg x-show="darkMode" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </button>

                    <!-- Login & Try Demo Buttons -->
                    <a href="{{ route('demo') }}" class="hidden sm:inline-flex px-3.5 py-1.5 text-xs font-semibold rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200 hover:bg-gray-200 transition">
                        Demo Accounts
                    </a>

                    <a href="/app" class="px-4 py-2 text-xs font-bold rounded-lg bg-violet-600 hover:bg-violet-700 text-white shadow-sm shadow-violet-500/20 transition">
                        Launch App →
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Body Content -->
    <main class="flex-grow">
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 mt-4">
                <div class="p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 rounded-xl text-emerald-800 dark:text-emerald-300 text-sm">
                    {{ session('success') }}
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="max-w-7xl mx-auto px-4 mt-4">
                <div class="p-4 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 rounded-xl text-rose-800 dark:text-rose-300 text-sm">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Comprehensive Footer -->
    <footer class="bg-white dark:bg-gray-950 border-t border-gray-200 dark:border-gray-800 mt-20 pt-16 pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-8 mb-12">
                <!-- Column 1: Brand & Positioning -->
                <div class="col-span-2">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-violet-600 flex items-center justify-center text-white font-bold text-sm">
                            AI
                        </div>
                        <span class="font-bold text-base text-gray-900 dark:text-white">{{ config('app.name') }}</span>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 max-w-sm leading-relaxed mb-4">
                        The world's most complete platform for generating, optimizing, translating, validating, reviewing, and bulk-publishing product listing content across Amazon, eBay, Shopify, Walmart, Etsy, and beyond.
                    </p>
                    <div class="text-xs text-gray-400">
                        Architected & Engineered by <a href="https://imrandev.bd" target="_blank" class="font-semibold text-violet-600 dark:text-violet-400 hover:underline">Imran Ahmed</a><br>
                        Contact: <a href="mailto:me@imrandev.bd" class="hover:underline">me@imrandev.bd</a> | <a href="https://imrandev.bd" target="_blank" class="hover:underline">https://imrandev.bd</a>
                    </div>
                </div>

                <!-- Column 2: Solutions -->
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-gray-900 dark:text-gray-200 mb-4">Solutions</h4>
                    <ul class="space-y-2 text-xs text-gray-600 dark:text-gray-400">
                        <li><a href="{{ route('solutions', 'solo') }}" class="hover:text-violet-600">Solo Sellers</a></li>
                        <li><a href="{{ route('solutions', 'dropshipper') }}" class="hover:text-violet-600">Dropshippers</a></li>
                        <li><a href="{{ route('solutions', 'brand') }}" class="hover:text-violet-600">Brands & Manufacturers</a></li>
                        <li><a href="{{ route('solutions', 'agency') }}" class="hover:text-violet-600">Marketplace Agencies</a></li>
                        <li><a href="{{ route('solutions', 'enterprise') }}" class="hover:text-violet-600">Enterprise Content Teams</a></li>
                        <li><a href="{{ route('solutions', 'wholesaler') }}" class="hover:text-violet-600">Wholesalers</a></li>
                    </ul>
                </div>

                <!-- Column 3: Platform & Docs -->
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-gray-900 dark:text-gray-200 mb-4">Platform & Docs</h4>
                    <ul class="space-y-2 text-xs text-gray-600 dark:text-gray-400">
                        <li><a href="{{ route('features') }}" class="hover:text-violet-600">All 48 Modules</a></li>
                        <li><a href="{{ route('marketplaces') }}" class="hover:text-violet-600">Channel Directory</a></li>
                        <li><a href="{{ route('pricing') }}" class="hover:text-violet-600">Pricing & Module Picker</a></li>
                        <li><a href="{{ route('demo') }}" class="hover:text-violet-600">Live Demo Sandbox</a></li>
                        <li><a href="{{ route('rules.library') }}" class="hover:text-violet-600">Rules Library</a></li>
                        <li><a href="{{ route('templates.gallery') }}" class="hover:text-violet-600">Templates Gallery</a></li>
                        <li><a href="{{ route('api.docs') }}" class="hover:text-violet-600">REST API & Webhooks</a></li>
                        <li><a href="{{ route('status') }}" class="hover:text-violet-600">System Status</a></li>
                        <li><a href="{{ route('changelog') }}" class="hover:text-violet-600">Changelog & Roadmap</a></li>
                    </ul>
                </div>

                <!-- Column 4: Legal & Company -->
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-gray-900 dark:text-gray-200 mb-4">Company & Legal</h4>
                    <ul class="space-y-2 text-xs text-gray-600 dark:text-gray-400">
                        <li><a href="{{ route('legal', 'about') }}" class="hover:text-violet-600">About Us</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-violet-600">Contact & Support</a></li>
                        <li><a href="{{ route('legal', 'careers') }}" class="hover:text-violet-600">Careers</a></li>
                        <li><a href="{{ route('legal', 'partners') }}" class="hover:text-violet-600">Affiliate Program</a></li>
                        <li><a href="{{ route('legal', 'reseller') }}" class="hover:text-violet-600">White-Label Reseller</a></li>
                        <li><a href="{{ route('legal', 'security') }}" class="hover:text-violet-600">Security & SSRF</a></li>
                        <li><a href="{{ route('legal', 'compliance') }}" class="hover:text-violet-600">Marketplace Compliance</a></li>
                        <li><a href="{{ route('legal', 'privacy') }}" class="hover:text-violet-600">Privacy Policy</a></li>
                        <li><a href="{{ route('legal', 'terms') }}" class="hover:text-violet-600">Terms of Service</a></li>
                        <li><a href="{{ route('legal', 'cookie') }}" class="hover:text-violet-600">Cookie Notice</a></li>
                        <li><a href="{{ route('legal', 'refund') }}" class="hover:text-violet-600">Refund Policy</a></li>
                        <li><a href="{{ route('legal', 'dpa') }}" class="hover:text-violet-600">Data Processing (DPA)</a></li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Credits -->
            <div class="pt-8 border-t border-gray-100 dark:border-gray-800/80 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-gray-400">
                <div>
                    © {{ date('Y') }} {{ config('app.name') }}. Built with Laravel 12 & Filament. All rights reserved.
                </div>
                <div class="flex items-center gap-4">
                    <span>⚡ Speed by DrutoGoti</span>
                    <span>🌐 Multilingual Unicode PDF</span>
                    <span>🔗 Universal Slugs</span>
                    <span>🤖 Unified AI Hub</span>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
