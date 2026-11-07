@extends('layouts.public')

@section('title', $rule->marketplace_name . ' Marketplace Rules & Limits')

@section('content')
<div class="py-16 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="{{ route('marketplaces') }}" class="text-xs font-semibold text-violet-600 hover:underline mb-2 inline-block">← All Channels</a>
        <h1 class="text-3xl sm:text-5xl font-extrabold text-gray-900 dark:text-white tracking-tight mb-4">
            {{ $rule->marketplace_name }} Listing Guidelines
        </h1>
        <p class="text-base text-gray-600 dark:text-gray-400 max-w-2xl">
            Autonomous compliance rule specifications enforced across our AI Studio and Bulk Engine for {{ $rule->marketplace_name }}.
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
        <div class="p-6 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700">
            <div class="text-xs font-semibold uppercase text-gray-400">Max Title Length</div>
            <div class="text-3xl font-extrabold text-violet-600 mt-2">{{ $rule->title_max_length }} chars</div>
        </div>
        <div class="p-6 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700">
            <div class="text-xs font-semibold uppercase text-gray-400">Max Bullet Points</div>
            <div class="text-3xl font-extrabold text-indigo-600 mt-2">{{ $rule->bullet_max_count }} items</div>
        </div>
        <div class="p-6 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700">
            <div class="text-xs font-semibold uppercase text-gray-400">Max Bullet Length</div>
            <div class="text-3xl font-extrabold text-emerald-600 mt-2">{{ $rule->bullet_max_length }} chars</div>
        </div>
        <div class="p-6 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700">
            <div class="text-xs font-semibold uppercase text-gray-400">Max Description Length</div>
            <div class="text-3xl font-extrabold text-blue-600 mt-2">{{ $rule->desc_max_length }} chars</div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 p-8 rounded-3xl border border-gray-200 dark:border-gray-700 shadow-sm">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Prohibited Words & Claims Checker</h3>
        <p class="text-xs text-gray-500 mb-4">The following claims and terms are strictly quarantined by our Compliance Checker when generating for {{ $rule->marketplace_name }}:</p>
        <div class="flex flex-wrap gap-2">
            @foreach($rule->forbidden_terms ?? ['#1', 'best seller', 'free shipping', 'miracle cure', 'cheapest'] as $term)
                <span class="px-3 py-1 bg-rose-50 text-rose-700 dark:bg-rose-950/40 dark:text-rose-300 text-xs font-semibold rounded-lg border border-rose-200 dark:border-rose-900">
                    ✕ {{ $term }}
                </span>
            @endforeach
        </div>
    </div>
</div>
@endsection
