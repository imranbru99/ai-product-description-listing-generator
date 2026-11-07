@extends('layouts.public')

@section('title', 'All 48 Modular Engines & Features')

@section('content')
<div class="py-16 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-4">Complete Modular Feature Catalogue</h1>
        <p class="text-base text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
            Explore all 48 self-contained modules engineered into this platform. Every module can be activated or deactivated per subscription plan and workspace without ever losing data.
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    @php
        $categories = [
            'core' => 'Core Systems (Always Active)',
            'catalog' => 'Product & Catalog Management (PIM-Lite)',
            'ai' => 'AI Generation & Content Engine',
            'marketplace' => 'Marketplaces, Channels & Feeds',
            'workflow' => 'Workflow, Approvals & Collaboration',
            'business' => 'Business Operations & Telemetry',
            'platform' => 'Platform, API & Developer Hub',
        ];
    @endphp

    @foreach($categories as $catKey => $catTitle)
        <div class="mb-14">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-800 pb-3 mb-6 flex items-center justify-between">
                <span>{{ $catTitle }}</span>
                <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-violet-100 text-violet-800 dark:bg-violet-950 dark:text-violet-300">
                    {{ count(array_filter($modules, fn($m) => $m['category'] === $catKey)) }} Modules
                </span>
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($modules as $key => $mod)
                    @if($mod['category'] === $catKey)
                        <div class="p-6 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col justify-between hover:border-violet-500 transition">
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-xs font-mono font-semibold px-2 py-0.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded">
                                        {{ $key }}
                                    </span>
                                    @if($mod['is_core'])
                                        <span class="text-[10px] font-bold uppercase px-2 py-0.5 bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-400 rounded">
                                            Core
                                        </span>
                                    @endif
                                </div>
                                <h3 class="text-base font-bold text-gray-900 dark:text-white mb-2">{{ $mod['name'] }}</h3>
                                <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed mb-4">{{ $mod['description'] }}</p>
                            </div>

                            <div class="pt-4 border-t border-gray-100 dark:border-gray-700/60 flex items-center justify-between text-xs">
                                <span class="text-gray-400">
                                    @if(!empty($mod['dependencies']))
                                        Req: {{ implode(', ', $mod['dependencies']) }}
                                    @else
                                        Standalone
                                    @endif
                                </span>
                                <a href="{{ route('features.detail', $key) }}" class="font-bold text-violet-600 dark:text-violet-400 hover:underline">
                                    View Specification →
                                </a>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endforeach
</div>
@endsection
