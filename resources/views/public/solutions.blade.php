@extends('layouts.public')

@section('title', 'Solutions for ' . $preset['name'])

@section('content')
<div class="py-16 bg-gradient-to-b from-violet-50/60 to-white dark:from-gray-950 dark:to-gray-900 border-b border-gray-200 dark:border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="inline-flex items-center gap-2 px-3 py-1 bg-violet-100 text-violet-800 dark:bg-violet-950 dark:text-violet-300 rounded-full text-xs font-bold uppercase mb-4">
            Persona Blueprint
        </div>
        <h1 class="text-4xl sm:text-5xl font-black text-gray-900 dark:text-white mb-4">
            Tailored Specifically for {{ $preset['name'] }}
        </h1>
        <p class="text-base text-gray-600 dark:text-gray-400 max-w-2xl mx-auto mb-8">
            {{ $preset['description'] }}
        </p>

        <!-- Persona Switcher Pills -->
        <div class="flex flex-wrap items-center justify-center gap-2">
            @foreach($presets as $pKey => $pData)
                <a
                    href="{{ route('solutions', $pKey) }}"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $persona === $pKey ? 'bg-violet-600 text-white shadow-md' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200' }}"
                >
                    {{ $pData['name'] }}
                </a>
            @endforeach
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">
        Recommended Active Modules for {{ $preset['name'] }} ({{ count($preset['modules']) }} Modules)
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-16">
        @foreach($preset['modules'] as $modKey)
            @php $mod = $allModules[$modKey] ?? null; @endphp
            @if($mod)
                <div class="p-6 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col justify-between">
                    <div>
                        <span class="text-xs font-semibold uppercase px-2 py-0.5 rounded bg-violet-50 text-violet-700 dark:bg-violet-950 dark:text-violet-300">
                            {{ $mod['category'] }}
                        </span>
                        <h3 class="text-base font-bold text-gray-900 dark:text-white mt-2 mb-1">{{ $mod['name'] }}</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">{{ $mod['description'] }}</p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center text-xs text-gray-400">
                        <span>Status: Preset Default</span>
                        <a href="{{ route('features.detail', $modKey) }}" class="font-bold text-violet-600 hover:underline">Learn more →</a>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    <!-- Live Sandbox CTA -->
    <div class="bg-gradient-to-r from-violet-600 to-indigo-700 text-white p-8 rounded-3xl text-center">
        <h3 class="text-2xl font-bold mb-2">Test the {{ $preset['name'] }} Workspace in Action</h3>
        <p class="text-xs text-violet-100 max-w-lg mx-auto mb-6">Our pre-seeded demo environment includes realistic sample catalogs, generated listings, and billing history for this exact persona.</p>
        <a href="{{ route('demo') }}" class="px-6 py-3 bg-white text-violet-700 rounded-xl font-bold text-xs hover:bg-violet-50 transition shadow">
            Launch Sandbox Now →
        </a>
    </div>
</div>
@endsection
