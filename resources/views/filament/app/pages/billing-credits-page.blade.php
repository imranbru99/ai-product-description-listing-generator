<x-filament-panels::page>
    <!-- Balance & Credit Packs Card -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-violet-600 to-indigo-800 text-white p-6 rounded-2xl shadow flex flex-col justify-between">
            <div>
                <div class="text-xs uppercase font-semibold text-violet-200">Current AI Credits</div>
                <div class="text-4xl font-extrabold mt-2">{{ number_format($creditsBalance) }}</div>
                <p class="text-xs text-violet-200 mt-2">1 credit = 1 comprehensive listing generation or 5 rewrites.</p>
            </div>
            <div class="mt-4">
                <span class="inline-flex items-center text-xs font-semibold px-2.5 py-1 bg-white/20 rounded-full">
                    Active Plan: {{ \Filament\Facades\Filament::getTenant()->plan?->name ?? 'Standard' }}
                </span>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 flex flex-col justify-between">
            <div>
                <div class="text-sm font-bold text-gray-900 dark:text-gray-100">Starter Pack</div>
                <div class="text-2xl font-black text-gray-900 dark:text-gray-100 mt-1">$19 <span class="text-xs text-gray-500 font-normal">/ one-time</span></div>
                <div class="text-sm text-indigo-600 font-semibold mt-2">500 Credits</div>
                <p class="text-xs text-gray-500 mt-1">Best for small stores adding 50-100 seasonal items.</p>
            </div>
            <div class="mt-4">
                <x-filament::button wire:click="buyPack(500, 19.00)" color="primary" class="w-full">
                    Purchase 500
                </x-filament::button>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border-2 border-violet-500 shadow-md flex flex-col justify-between relative">
            <span class="absolute -top-3 right-4 px-2.5 py-0.5 bg-violet-600 text-white text-[10px] font-bold uppercase rounded-full tracking-wider">
                Most Popular
            </span>
            <div>
                <div class="text-sm font-bold text-gray-900 dark:text-gray-100">Growth Pack</div>
                <div class="text-2xl font-black text-gray-900 dark:text-gray-100 mt-1">$69 <span class="text-xs text-gray-500 font-normal">/ one-time</span></div>
                <div class="text-sm text-indigo-600 font-semibold mt-2">2,500 Credits</div>
                <p class="text-xs text-gray-500 mt-1">Ideal for expanding dropshippers and active brands.</p>
            </div>
            <div class="mt-4">
                <x-filament::button wire:click="buyPack(2500, 69.00)" color="primary" class="w-full">
                    Purchase 2,500
                </x-filament::button>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 flex flex-col justify-between">
            <div>
                <div class="text-sm font-bold text-gray-900 dark:text-gray-100">Agency Pack</div>
                <div class="text-2xl font-black text-gray-900 dark:text-gray-100 mt-1">$199 <span class="text-xs text-gray-500 font-normal">/ one-time</span></div>
                <div class="text-sm text-indigo-600 font-semibold mt-2">10,000 Credits</div>
                <p class="text-xs text-gray-500 mt-1">High-volume catalog updates and bulk feed processing.</p>
            </div>
            <div class="mt-4">
                <x-filament::button wire:click="buyPack(10000, 199.00)" color="gray" class="w-full">
                    Purchase 10,000
                </x-filament::button>
            </div>
        </div>
    </div>

    <!-- Recent Invoices Table -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Invoice History & Unicode PDF Downloads</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                <thead class="bg-gray-50 dark:bg-gray-900/50 text-xs uppercase font-semibold text-gray-500">
                    <tr>
                        <th class="p-3">Invoice Number</th>
                        <th class="p-3">Date</th>
                        <th class="p-3">Amount</th>
                        <th class="p-3">Gateway</th>
                        <th class="p-3">Status</th>
                        <th class="p-3 text-right">PDF Download</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse(\Filament\Facades\Filament::getTenant()->invoices()->latest()->take(10)->get() as $inv)
                        <tr>
                            <td class="p-3 font-semibold text-gray-900 dark:text-gray-100">{{ $inv->invoice_number }}</td>
                            <td class="p-3">{{ $inv->created_at->format('M d, Y') }}</td>
                            <td class="p-3 font-medium text-gray-900 dark:text-gray-200">{{ $inv->currency }} {{ number_format($inv->amount, 2) }}</td>
                            <td class="p-3"><span class="capitalize">{{ $inv->payment_gateway }}</span></td>
                            <td class="p-3">
                                <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-400">
                                    {{ strtoupper($inv->status) }}
                                </span>
                            </td>
                            <td class="p-3 text-right">
                                <button type="button" wire:click="downloadInvoice({{ $inv->id }})" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 inline-flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    PDF Invoice
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-4 text-center text-gray-400">No invoices generated yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-filament-panels::page>
