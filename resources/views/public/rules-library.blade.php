@extends('layouts.public')

@section('title', 'Marketplace Compliance & Quality Rules Library')

@section('content')
<div class="py-16 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 text-center">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-4">Marketplace Rules Library</h1>
        <p class="text-base text-gray-600 dark:text-gray-400 max-w-xl mx-auto">
            Public reference directory for marketplace character bounds, bullet limits, and banned terms.
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-3xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
        <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
            <thead class="bg-gray-50 dark:bg-gray-900/60 uppercase text-xs font-semibold text-gray-500">
                <tr>
                    <th class="p-3">Marketplace</th>
                    <th class="p-3">Max Title</th>
                    <th class="p-3">Max Bullets</th>
                    <th class="p-3">Bullet Char Limit</th>
                    <th class="p-3">Max Description</th>
                    <th class="p-3">Prohibited Claims</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach($rules as $r)
                    <tr>
                        <td class="p-3 font-bold text-gray-900 dark:text-white">{{ $r->marketplace_name }}</td>
                        <td class="p-3 font-semibold text-violet-600">{{ $r->title_max_length }} chars</td>
                        <td class="p-3">{{ $r->bullet_max_count }} items</td>
                        <td class="p-3">{{ $r->bullet_max_length }} chars</td>
                        <td class="p-3">{{ $r->desc_max_length }} chars</td>
                        <td class="p-3 text-xs text-rose-500">
                            {{ implode(', ', array_slice($r->forbidden_terms ?? ['#1', 'best seller', 'free shipping'], 0, 3)) }}...
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
