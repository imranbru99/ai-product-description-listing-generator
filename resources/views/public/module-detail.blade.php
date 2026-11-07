@extends('layouts.public')

@section('title', $module['name'] . ' Module Specification')

@section('content')
<div class="py-16 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <div class="flex items-center gap-3 mb-4">
                <a href="{{ route('features') }}" class="text-xs font-semibold text-violet-600 hover:underline">← All 48 Modules</a>
                <span class="text-gray-300">/</span>
                <span class="text-xs font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-violet-100 text-violet-800 dark:bg-violet-950 dark:text-violet-300">
                    Category: {{ $module['category'] }}
                </span>
            </div>
            <h1 class="text-3xl sm:text-5xl font-extrabold text-gray-900 dark:text-white tracking-tight mb-4">
                {{ $module['name'] }}
            </h1>
            <p class="text-lg text-gray-600 dark:text-gray-300 leading-relaxed">
                {{ $module['description'] }}
            </p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <div class="lg:col-span-2 space-y-8">
            <!-- Architecture & Capabilities Card -->
            <div class="bg-white dark:bg-gray-800 p-8 rounded-3xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Architecture & Capabilities</h3>
                <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed mb-6">
                    The <strong>{{ $module['name'] }}</strong> engine operates as an autonomous, decoupled micro-feature inside the Laravel container. When active, it registers scoped Filament panel resources, background queue workers, and REST API endpoints.
                </p>

                <div class="p-6 bg-gray-50 dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 mb-6">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-3">Key Technical Guarantees</h4>
                    <ul class="space-y-2.5 text-xs text-gray-700 dark:text-gray-300">
                        <li class="flex items-center gap-2">✓ <strong>Strict Multi-Tenant Isolation:</strong> Data rows are scoped using global workspace tenant scopes.</li>
                        <li class="flex items-center gap-2">✓ <strong>Zero Data Loss on Deactivation:</strong> Disabling the module only conceals UI and API layers; historical records are fully preserved.</li>
                        <li class="flex items-center gap-2">✓ <strong>Asynchronous Queue Worker Safe:</strong> Intensive tasks offload to non-blocking background queue jobs.</li>
                        <li class="flex items-center gap-2">✓ <strong>Offline Demo Emulation:</strong> Includes deterministic simulation drivers so you never require external API keys for testing.</li>
                    </ul>
                </div>
            </div>

            <!-- E-Commerce Use Cases -->
            <div class="bg-white dark:bg-gray-800 p-8 rounded-3xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Practical E-Commerce Use Cases</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs text-gray-600 dark:text-gray-300">
                    <div class="p-4 rounded-xl bg-violet-50/50 dark:bg-gray-900/50 border border-violet-100 dark:border-gray-700">
                        <div class="font-bold text-gray-900 dark:text-white mb-1">Single-Click Catalog Sync</div>
                        <p>Streamline product listing creation and syndicate across multiple digital storefronts in seconds.</p>
                    </div>
                    <div class="p-4 rounded-xl bg-violet-50/50 dark:bg-gray-900/50 border border-violet-100 dark:border-gray-700">
                        <div class="font-bold text-gray-900 dark:text-white mb-1">Quality & Compliance Shield</div>
                        <p>Automatically detect banned terms, title overages, and trademark violations before listings go live.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar: Specifications & Subscription Plans -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-4">Module Specification</h4>
                <div class="space-y-3 text-xs">
                    <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-gray-500">Module Key:</span>
                        <span class="font-mono font-semibold">{{ $module['key'] }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-gray-500">Core Status:</span>
                        <span class="font-semibold">{{ $module['is_core'] ? 'Core (Always Active)' : 'Optional / Toggleable' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-gray-500">Dependencies:</span>
                        <span class="font-semibold">{{ empty($module['dependencies']) ? 'None' : implode(', ', $module['dependencies']) }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-violet-600 to-indigo-700 text-white p-6 rounded-2xl shadow">
                <h4 class="text-base font-bold mb-2">Ready to Test This Module?</h4>
                <p class="text-xs text-violet-100 mb-4">Log into our pre-seeded live sandbox as any role and try {{ $module['name'] }} immediately.</p>
                <a href="{{ route('demo') }}" class="block w-full py-2.5 bg-white text-violet-700 text-center rounded-xl text-xs font-bold hover:bg-violet-50 transition">
                    Launch Demo Sandbox →
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
