@extends('layouts.public')

@section('title', 'Prompt & Template Marketplace Gallery')

@section('content')
<div class="py-16 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 text-center">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-4">Prompt & Template Library</h1>
        <p class="text-base text-gray-600 dark:text-gray-400 max-w-xl mx-auto">
            Battle-tested e-commerce listing prompts engineered for high conversion, compliance, and SEO rankings.
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($templates as $tmpl)
            <div class="p-6 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold uppercase px-2 py-0.5 rounded bg-violet-100 text-violet-800 dark:bg-violet-950 dark:text-violet-300">
                            {{ $tmpl->marketplace }}
                        </span>
                        <span class="text-[10px] text-gray-400">v{{ $tmpl->version }}</span>
                    </div>
                    <h3 class="text-base font-bold text-gray-900 dark:text-white mb-2">{{ $tmpl->name }}</h3>
                    <div class="p-3 bg-gray-50 dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 text-xs font-mono text-gray-600 dark:text-gray-400 line-clamp-4">
                        {{ $tmpl->user_prompt_template }}
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center text-xs">
                    <span class="text-gray-400">Category: {{ ucfirst($tmpl->category) }}</span>
                    <a href="/app" class="font-bold text-violet-600 hover:underline">Use Template →</a>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-12 text-gray-500">
                Templates will load from DemoSeeder.
            </div>
        @endforelse
    </div>
</div>
@endsection
