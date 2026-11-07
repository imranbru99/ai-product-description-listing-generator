@extends('layouts.public')

@section('title', 'Live Interactive Demo Sandbox & Role Accounts')

@section('content')
<div class="py-16 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 text-xs font-bold uppercase rounded-full mb-4">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            Zero Setup · Instant 1-Click Access
        </span>
        <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-4">Live Multi-Role Demo Sandbox</h1>
        <p class="text-base text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
            Experience each role's dedicated panel and dashboard. Click any role button below to instantly log in as that user with zero credentials to type.
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <!-- Panel 1: Platform Staff & Super Admin -->
    <div class="mb-14">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
            <span class="p-1.5 bg-indigo-100 text-indigo-700 rounded-lg text-sm">🛡️</span>
            Platform Staff & Super Admin Panel (/superadmin)
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
            <div class="p-5 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col justify-between">
                <div>
                    <h3 class="font-bold text-sm text-gray-900 dark:text-white">Super Admin</h3>
                    <p class="text-xs text-gray-500 mt-1">Platform owner. Workspaces, global modules, plans, DrutoGoti metrics.</p>
                </div>
                <a href="{{ route('demo.login', 'superadmin') }}" class="mt-4 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold text-center transition">
                    Log In as Super Admin →
                </a>
            </div>

            <div class="p-5 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col justify-between">
                <div>
                    <h3 class="font-bold text-sm text-gray-900 dark:text-white">Support Agent</h3>
                    <p class="text-xs text-gray-500 mt-1">Tickets queue, SLA management, canned replies, live chat.</p>
                </div>
                <a href="{{ route('demo.login', 'support') }}" class="mt-4 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold text-center transition">
                    Log In as Support →
                </a>
            </div>

            <div class="p-5 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col justify-between">
                <div>
                    <h3 class="font-bold text-sm text-gray-900 dark:text-white">Finance Manager</h3>
                    <p class="text-xs text-gray-500 mt-1">Subscription plans, credit transactions, tax and gateway logs.</p>
                </div>
                <a href="{{ route('demo.login', 'finance') }}" class="mt-4 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold text-center transition">
                    Log In as Finance →
                </a>
            </div>

            <div class="p-5 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col justify-between">
                <div>
                    <h3 class="font-bold text-sm text-gray-900 dark:text-white">Content Moderator</h3>
                    <p class="text-xs text-gray-500 mt-1">Marketplace policy compliance and trademark reviews.</p>
                </div>
                <a href="{{ route('demo.login', 'moderator') }}" class="mt-4 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold text-center transition">
                    Log In as Moderator →
                </a>
            </div>

            <div class="p-5 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col justify-between">
                <div>
                    <h3 class="font-bold text-sm text-gray-900 dark:text-white">Sales & CRM Manager</h3>
                    <p class="text-xs text-gray-500 mt-1">Lead pipelines, enterprise inquiries, workspace onboarding.</p>
                </div>
                <a href="{{ route('demo.login', 'sales') }}" class="mt-4 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold text-center transition">
                    Log In as Sales →
                </a>
            </div>
        </div>
    </div>

    <!-- Panel 2: Workspace Panel -->
    <div class="mb-14">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
            <span class="p-1.5 bg-violet-100 text-violet-700 rounded-lg text-sm">🏢</span>
            Business Workspace Panel (/app)
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <div class="p-5 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col justify-between">
                <div>
                    <h3 class="font-bold text-sm text-gray-900 dark:text-white">Workspace Owner</h3>
                    <p class="text-xs text-gray-500 mt-1">Full control of workspace settings, modules marketplace, and billing.</p>
                </div>
                <a href="{{ route('demo.login', 'owner') }}" class="mt-4 px-4 py-2 bg-violet-600 hover:bg-violet-700 text-white rounded-xl text-xs font-bold text-center transition">
                    Log In as Owner →
                </a>
            </div>

            <div class="p-5 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col justify-between">
                <div>
                    <h3 class="font-bold text-sm text-gray-900 dark:text-white">Content Writer / Editor</h3>
                    <p class="text-xs text-gray-500 mt-1">AI Studio copy generation, tone tuning, and prompt template editing.</p>
                </div>
                <a href="{{ route('demo.login', 'writer') }}" class="mt-4 px-4 py-2 bg-violet-600 hover:bg-violet-700 text-white rounded-xl text-xs font-bold text-center transition">
                    Log In as Writer →
                </a>
            </div>

            <div class="p-5 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col justify-between">
                <div>
                    <h3 class="font-bold text-sm text-gray-900 dark:text-white">SEO Specialist</h3>
                    <p class="text-xs text-gray-500 mt-1">Search term density, JSON-LD schema, title length validation.</p>
                </div>
                <a href="{{ route('demo.login', 'seo') }}" class="mt-4 px-4 py-2 bg-violet-600 hover:bg-violet-700 text-white rounded-xl text-xs font-bold text-center transition">
                    Log In as SEO Specialist →
                </a>
            </div>

            <div class="p-5 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col justify-between">
                <div>
                    <h3 class="font-bold text-sm text-gray-900 dark:text-white">Translator / Localization</h3>
                    <p class="text-xs text-gray-500 mt-1">Multilingual adaptations, Bengali/Arabic RTL, glossary locks.</p>
                </div>
                <a href="{{ route('demo.login', 'translator') }}" class="mt-4 px-4 py-2 bg-violet-600 hover:bg-violet-700 text-white rounded-xl text-xs font-bold text-center transition">
                    Log In as Translator →
                </a>
            </div>
        </div>
    </div>

    <!-- Panel 3 & 4: Client & Partner Portals -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-14">
        <div class="p-8 rounded-3xl bg-emerald-50/50 dark:bg-gray-800 border border-emerald-200 dark:border-emerald-800/60 flex flex-col justify-between">
            <div>
                <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 text-[10px] font-bold uppercase rounded-full">External Portal</span>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mt-3">Agency Client Approval Portal (/client)</h3>
                <p class="text-xs text-gray-600 dark:text-gray-300 mt-2 leading-relaxed">
                    Designed for clients of marketplace agencies. View listings prepared for your brand, review side-by-side diffs, request changes with comments, and download branded line-sheet PDFs.
                </p>
            </div>
            <a href="{{ route('demo.login', 'client') }}" class="mt-6 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold text-center transition">
                Log In as Agency Client →
            </a>
        </div>

        <div class="p-8 rounded-3xl bg-blue-50/50 dark:bg-gray-800 border border-blue-200 dark:border-blue-800/60 flex flex-col justify-between">
            <div>
                <span class="px-2.5 py-1 bg-blue-100 text-blue-800 text-[10px] font-bold uppercase rounded-full">Partner Portal</span>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mt-3">Affiliate & Reseller Portal (/partner)</h3>
                <p class="text-xs text-gray-600 dark:text-gray-300 mt-2 leading-relaxed">
                    Designed for marketing affiliates and white-label resellers. Track unique referral links, conversions, earned commissions, and request payouts.
                </p>
            </div>
            <a href="{{ route('demo.login', 'partner') }}" class="mt-6 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold text-center transition">
                Log In as Affiliate Partner →
            </a>
        </div>
    </div>

    <!-- Credentials Reference Table -->
    <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-200 dark:border-gray-700 p-8">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Demo Credentials Matrix (Default Password: <code class="bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded font-mono">password</code>)</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-600 dark:text-gray-400">
                <thead class="bg-gray-50 dark:bg-gray-900 font-semibold uppercase text-gray-500">
                    <tr>
                        <th class="p-3">Role</th>
                        <th class="p-3">Email Address</th>
                        <th class="p-3">Panel Route</th>
                        <th class="p-3">Default Workspace</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    <tr><td class="p-3 font-semibold">Super Admin</td><td class="p-3 font-mono">superadmin@demo.test</td><td class="p-3">/superadmin</td><td class="p-3">Platform Global</td></tr>
                    <tr><td class="p-3 font-semibold">Workspace Owner</td><td class="p-3 font-mono">owner@demo.test</td><td class="p-3">/app/dhaka-gadgets</td><td class="p-3">Dhaka Gadgets (Solo)</td></tr>
                    <tr><td class="p-3 font-semibold">Content Writer</td><td class="p-3 font-mono">writer@demo.test</td><td class="p-3">/app/aurora-home</td><td class="p-3">Aurora Home (Brand)</td></tr>
                    <tr><td class="p-3 font-semibold">Client User</td><td class="p-3 font-mono">client@demo.test</td><td class="p-3">/client</td><td class="p-3">ContentForge Agency Client</td></tr>
                    <tr><td class="p-3 font-semibold">Affiliate Partner</td><td class="p-3 font-mono">partner@demo.test</td><td class="p-3">/partner</td><td class="p-3">Global Affiliate</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
