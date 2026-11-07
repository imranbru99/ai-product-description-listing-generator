<x-filament-panels::page>
    <!-- Business Persona Presets Quick-Switch -->
    <div class="bg-gradient-to-r from-violet-600 to-indigo-700 text-white p-6 rounded-2xl shadow-sm mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h3 class="text-xl font-bold">Business Persona Presets</h3>
                <p class="text-violet-100 text-sm mt-1">One-click activation tailored to your e-commerce business model.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <button type="button" wire:click="applyPreset('solo')" class="px-3 py-1.5 bg-white/20 hover:bg-white/30 rounded-lg text-xs font-semibold backdrop-blur transition">
                    Solo Seller
                </button>
                <button type="button" wire:click="applyPreset('dropshipper')" class="px-3 py-1.5 bg-white/20 hover:bg-white/30 rounded-lg text-xs font-semibold backdrop-blur transition">
                    Dropshipper
                </button>
                <button type="button" wire:click="applyPreset('brand')" class="px-3 py-1.5 bg-white/20 hover:bg-white/30 rounded-lg text-xs font-semibold backdrop-blur transition">
                    Brand / Manufacturer
                </button>
                <button type="button" wire:click="applyPreset('agency')" class="px-3 py-1.5 bg-white/20 hover:bg-white/30 rounded-lg text-xs font-semibold backdrop-blur transition">
                    Agency Suite
                </button>
                <button type="button" wire:click="applyPreset('enterprise')" class="px-3 py-1.5 bg-white/20 hover:bg-white/30 rounded-lg text-xs font-semibold backdrop-blur transition">
                    Enterprise (All)
                </button>
                <button type="button" wire:click="applyPreset('wholesaler')" class="px-3 py-1.5 bg-white/20 hover:bg-white/30 rounded-lg text-xs font-semibold backdrop-blur transition">
                    Wholesaler
                </button>
            </div>
        </div>
    </div>

    <!-- Modules Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($modules as $key => $mod)
            <div class="bg-white dark:bg-gray-800 rounded-xl border {{ $mod['is_active'] ? 'border-violet-300 dark:border-violet-700 shadow-sm' : 'border-gray-200 dark:border-gray-700 opacity-80' }} p-5 flex flex-col justify-between transition-all hover:border-violet-400">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold uppercase tracking-wider px-2 py-0.5 rounded-full {{ $mod['is_active'] ? 'bg-violet-100 text-violet-800 dark:bg-violet-900/40 dark:text-violet-300' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }}">
                            {{ $mod['category'] }}
                        </span>
                        @if($mod['is_core'])
                            <span class="text-[10px] font-bold uppercase px-2 py-0.5 bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-400 rounded">
                                Core (Always On)
                            </span>
                        @else
                            <button
                                type="button"
                                wire:click="toggleModule('{{ $key }}')"
                                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $mod['is_active'] ? 'bg-violet-600' : 'bg-gray-300 dark:bg-gray-600' }}"
                                role="switch"
                                aria-checked="{{ $mod['is_active'] ? 'true' : 'false' }}"
                            >
                                <span class="sr-only">Toggle {{ $mod['name'] }}</span>
                                <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $mod['is_active'] ? 'translate-x-5' : 'translate-x-0' }}"></span>
                            </button>
                        @endif
                    </div>

                    <h4 class="text-base font-bold text-gray-900 dark:text-gray-100 mb-1">
                        {{ $mod['name'] }}
                    </h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-3 mb-3">
                        {{ $mod['description'] }}
                    </p>
                </div>

                <div class="pt-3 border-t border-gray-100 dark:border-gray-700/60 flex items-center justify-between text-xs text-gray-500">
                    <div>
                        @if(!empty($mod['dependencies']))
                            <span title="Requires: {{ implode(', ', $mod['dependencies']) }}" class="text-[11px] text-gray-400">
                                🔗 {{ count($mod['dependencies']) }} Req.
                            </span>
                        @else
                            <span class="text-[11px] text-gray-400">Standalone</span>
                        @endif
                    </div>
                    <div class="font-medium">
                        Usage: <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $mod['usage_count'] }}</span> ops
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-filament-panels::page>
