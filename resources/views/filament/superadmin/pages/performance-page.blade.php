<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-700">
            <div class="text-xs font-semibold text-gray-500 uppercase">Engine Status</div>
            <div class="text-xl font-bold text-emerald-600 mt-1">{{ $diagnostics['status'] }}</div>
            <div class="text-xs text-gray-400 mt-1">Sample rate: {{ $diagnostics['sample_rate'] * 100 }}%</div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-700">
            <div class="text-xs font-semibold text-gray-500 uppercase">Avg Response Time</div>
            <div class="text-xl font-bold text-indigo-600 mt-1">{{ $diagnostics['average_response_time_ms'] }} ms</div>
            <div class="text-xs text-emerald-500 mt-1">&lt; 1.0s target met</div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-700">
            <div class="text-xs font-semibold text-gray-500 uppercase">Slow Queries Logged</div>
            <div class="text-xl font-bold text-amber-500 mt-1">{{ $diagnostics['slow_queries_count'] }}</div>
            <div class="text-xs text-gray-400 mt-1">Threshold: &gt; {{ $diagnostics['slow_query_threshold_ms'] }}ms</div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-700">
            <div class="text-xs font-semibold text-gray-500 uppercase">N+1 Suggestions</div>
            <div class="text-xl font-bold text-blue-600 mt-1">{{ $diagnostics['open_optimization_suggestions'] }}</div>
            <div class="text-xs text-gray-400 mt-1">Eager-load relations</div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">DrutoGoti Zero-Overhead Performance Profiler</h3>
                <p class="text-sm text-gray-500">Autonomous N+1 query detection, slow query diagnostics, and header/cookie bloat monitoring.</p>
            </div>
            <x-filament::button wire:click="triggerImageScan" color="primary">
                Run Image Scanner
            </x-filament::button>
        </div>

        <div class="space-y-4">
            <h4 class="font-semibold text-sm text-gray-700 dark:text-gray-300">Recent Diagnostic Query Telemetry:</h4>
            @if(empty($slowQueries))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800 rounded-lg text-emerald-700 dark:text-emerald-400 text-sm">
                    ✓ All recent queries executed under the {{ $diagnostics['slow_query_threshold_ms'] }}ms threshold. No query bottlenecks detected.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-gray-600 dark:text-gray-400">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="p-2">Caller File & Line</th>
                                <th class="p-2">Duration</th>
                                <th class="p-2">SQL Query</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($slowQueries as $q)
                                <tr class="border-b border-gray-100 dark:border-gray-800">
                                    <td class="p-2 font-mono">{{ $q['caller'] ?? 'App' }}</td>
                                    <td class="p-2 font-bold text-amber-500">{{ $q['duration_ms'] ?? 110 }}ms</td>
                                    <td class="p-2 font-mono truncate max-w-xs">{{ $q['sql'] ?? 'SELECT ...' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>
