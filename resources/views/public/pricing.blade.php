@extends('layouts.public')

@section('title', 'Simple, Transparent Pricing & Custom Plan Builder')

@section('content')
<div class="py-16 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800" x-data="{ annual: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl sm:text-5xl font-black text-gray-900 dark:text-white tracking-tight mb-4">
            Predictable Pricing for High-Volume Commerce
        </h1>
        <p class="text-base text-gray-600 dark:text-gray-400 max-w-xl mx-auto mb-8">
            Choose a pre-configured tier, buy credits on-demand, or use our interactive module picker below to build your exact package.
        </p>

        <!-- Annual / Monthly Toggle -->
        <div class="inline-flex items-center gap-3 p-1 rounded-full bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
            <button
                type="button"
                @click="annual = false"
                :class="!annual ? 'bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm' : 'text-gray-500'"
                class="px-5 py-2 rounded-full text-xs font-bold transition"
            >
                Monthly Billing
            </button>
            <button
                type="button"
                @click="annual = true"
                :class="annual ? 'bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm' : 'text-gray-500'"
                class="px-5 py-2 rounded-full text-xs font-bold transition flex items-center gap-1.5"
            >
                <span>Annual Billing</span>
                <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 text-[10px] font-extrabold rounded-full">Save 20%</span>
            </button>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16" x-data="{ annual: false }">
    <!-- Plan Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-20">
        @foreach($plans as $plan)
            <div class="p-6 rounded-3xl border {{ $plan->is_popular ? 'border-2 border-violet-500 shadow-xl' : 'border-gray-200 dark:border-gray-800 shadow-sm' }} bg-white dark:bg-gray-800 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-base font-bold text-gray-900 dark:text-white">{{ $plan->name }}</h3>
                        @if($plan->is_popular)
                            <span class="px-2 py-0.5 bg-violet-100 text-violet-800 text-[10px] font-bold uppercase rounded-full">Popular</span>
                        @endif
                    </div>
                    <div class="text-3xl font-extrabold text-gray-900 dark:text-white mb-2">
                        <span x-show="!annual">${{ number_format($plan->price_monthly, 0) }}</span>
                        <span x-show="annual" x-cloak>${{ number_format($plan->price_yearly / 12, 0) }}</span>
                        <span class="text-xs font-normal text-gray-500">/ mo</span>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-6">{{ $plan->description }}</p>

                    <div class="space-y-2.5 text-xs text-gray-600 dark:text-gray-300 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <div class="flex items-center gap-2">✓ <strong>{{ number_format($plan->monthly_credits) }}</strong> AI Credits/mo</div>
                        <div class="flex items-center gap-2">✓ <strong>{{ number_format($plan->max_products) }}</strong> Products</div>
                        <div class="flex items-center gap-2">✓ <strong>{{ $plan->max_users }}</strong> User Seats</div>
                        <div class="flex items-center gap-2">✓ Unicode PDF Engine</div>
                    </div>
                </div>

                <a href="{{ route('demo') }}" class="w-full mt-8 py-2.5 rounded-xl text-center text-xs font-bold {{ $plan->is_popular ? 'bg-violet-600 text-white hover:bg-violet-700' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-200' }} transition">
                    Try Free as Demo User
                </a>
            </div>
        @endforeach
    </div>

    <!-- Interactive "Build Your Own Plan" / Module Picker -->
    <div class="bg-gradient-to-br from-violet-900 to-indigo-950 text-white p-8 sm:p-12 rounded-3xl shadow-xl mb-20" x-data="{
        selected: ['auth', 'settings', 'catalog', 'ai_studio', 'marketplace_rules', 'billing', 'pdf_center'],
        basePrice: 19,
        perModulePrice: 4,
        toggle(k) {
            if (['auth', 'settings', 'billing', 'module_manager'].includes(k)) return; // Core locked
            if (this.selected.includes(k)) {
                this.selected = this.selected.filter(x => x !== k);
            } else {
                this.selected.push(k);
            }
        },
        get estimated() {
            return this.basePrice + (Math.max(0, this.selected.length - 4) * this.perModulePrice);
        }
    }">
        <div class="flex flex-col lg:flex-row items-start justify-between gap-8 mb-8 pb-8 border-b border-violet-800/80">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-violet-300 bg-white/10 px-3 py-1 rounded-full">
                    Interactive Customizer
                </span>
                <h3 class="text-2xl sm:text-3xl font-extrabold mt-3">Build Your Custom Plan</h3>
                <p class="text-sm text-violet-200 mt-1 max-w-xl">
                    Select exactly the capabilities your team requires. Pay only for what you use with zero overhead.
                </p>
            </div>

            <div class="bg-white/10 p-6 rounded-2xl border border-white/20 text-center min-w-[220px]">
                <div class="text-xs text-violet-200 font-semibold uppercase">Estimated Monthly Price</div>
                <div class="text-4xl font-black mt-1">$<span x-text="estimated"></span></div>
                <div class="text-xs text-violet-300 mt-1"><span x-text="selected.length"></span> Modules Activated</div>
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 text-xs">
            @foreach($modules as $key => $mod)
                <button
                    type="button"
                    @click="toggle('{{ $key }}')"
                    :class="selected.includes('{{ $key }}') ? 'bg-violet-600 text-white border-violet-400' : 'bg-white/5 text-violet-200 border-white/10 hover:bg-white/10'"
                    class="p-3 rounded-xl border text-left flex items-center justify-between transition"
                >
                    <span class="font-semibold truncate mr-2">{{ $mod['name'] }}</span>
                    <span x-text="selected.includes('{{ $key }}') ? '✓' : '+'" class="font-bold"></span>
                </button>
            @endforeach
        </div>
    </div>
</div>
@endsection
