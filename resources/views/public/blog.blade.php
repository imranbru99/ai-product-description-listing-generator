@extends('layouts.public')

@section('title', 'E-Commerce Intelligence & AI Listing Strategy Blog')

@section('content')
<div class="py-16 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 text-center">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-4">E-Commerce Strategy & Engineering Blog</h1>
        <p class="text-base text-gray-600 dark:text-gray-400 max-w-xl mx-auto">
            Insights on autonomous catalog operations, multi-marketplace SEO, EU Digital Product Passport compliance, and conversion rate optimization.
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
        @foreach($articles as $art)
            <article class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm flex flex-col justify-between hover:border-violet-500 transition">
                <div class="p-6">
                    <div class="flex items-center justify-between text-xs text-gray-400 mb-3">
                        <span class="px-2.5 py-0.5 rounded-full bg-violet-100 text-violet-800 font-bold uppercase text-[10px]">{{ $art->category?->name ?? 'Strategy' }}</span>
                        <span>{{ $art->created_at->format('M d, Y') }}</span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2 leading-snug">
                        <a href="{{ route('blog.post', $art->slug) }}" class="hover:text-violet-600 transition">{{ $art->title }}</a>
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed line-clamp-3 mb-4">
                        {{ $art->excerpt }}
                    </p>
                </div>
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/60 border-t border-gray-100 dark:border-gray-700/60 flex items-center justify-between text-xs">
                    <span class="text-gray-400">{{ $art->views_count }} reads</span>
                    <a href="{{ route('blog.post', $art->slug) }}" class="font-bold text-violet-600 hover:underline">Read article →</a>
                </div>
            </article>
        @endforeach
    </div>

    <div>
        {{ $articles->links() }}
    </div>
</div>
@endsection
