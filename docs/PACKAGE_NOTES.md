# Package Analysis & Integration Notes

This document provides a technical audit and integration architecture for the seven required core packages authored by **Imran Dev BD** (`me@imrandev.bd`, [imrandev.bd](https://imrandev.bd)), detailing their exact capabilities, facades, APIs, configuration options, and how they are leveraged across this SaaS application alongside Filament and Laravel.

---

## 1. `imrandevbd/drutogoti` (⚡ দ্রুতগতি Application Profiler & Optimizer)

### Package Overview
- **Packagist:** `imrandevbd/drutogoti` (dev-main)
- **Repository:** `imranbru99/laravel-speed-optimization-drutogoti`
- **Namespace:** `Drutogoti\`
- **Service Provider:** `Drutogoti\DrutogotiServiceProvider`
- **Config Published:** `config/drutogoti.php`
- **Role:** Autonomous detection and optimization of N+1 database queries, slow queries (> threshold), cache telemetry (hits/misses), cookie & header bloat alerts (> 4KB), uncompressed asset scanner, and background queue offloading.

### Key Classes & APIs
- **Middleware:** `Drutogoti\Http\Middleware\DrutogotiMiddleware` (captures request duration, memory usage, queries, and offloads asynchronously to terminating callbacks).
- **Models:**
  - `Drutogoti\Models\DrutogotiRequest`: Logs URL, method, status, response time, memory, query count, cache hit ratio.
  - `Drutogoti\Models\DrutogotiQueryLog`: Records slow queries with caller file, line, and execution time in ms.
  - `Drutogoti\Models\DrutogotiSuggestion`: Grouped N+1 query patterns, severity ratings, and recommended eager-loading relations.
  - `Drutogoti\Models\DrutogotiCacheEvent`: Cache telemetry metrics.
  - `Drutogoti\Models\DrutogotiCookieEvent`: Oversized cookie/header tracking.
  - `Drutogoti\Models\DrutogotiImageScan`: Unoptimized images found in public assets directory.
- **Commands:**
  - `php artisan drutogoti:scan-images`: Scans public media assets for uncompressed payloads.
  - `php artisan drutogoti:clean`: Purges aged telemetry data according to retention policy.
- **Facade / Helpers:** `Drutogoti\Facades\Drutogoti`

### SaaS Integration Plan
- Built a dedicated **Performance & System Speed Page** in the Super Admin panel displaying real-time database query counts, N+1 alerts, response time graphs, and toggle controls for sampling rates and slow query thresholds.
- Hooked into public routes and bulk AI queues to guarantee sub-1s TTFB and zero-overhead background telemetry.

---

## 2. `imrandevbd/laravel-admin-panel` (Modern Admin Shell & UI Framework)

### Package Overview
- **Packagist:** `imrandevbd/laravel-admin-panel` (dev-main)
- **Repository:** `imranbru99/laravel-admin-panel`
- **Namespace:** `ImranDevBD\LaravelAdminPanel\`
- **Service Provider:** `ImranDevBD\LaravelAdminPanel\AdminPanelServiceProvider`
- **Config Published:** `config/admin-panel.php`
- **Assets Published:** `public/vendor/admin-panel` (Zero-build precompiled Alpine.js 3 & CSS distribution)
- **Role:** Complementary admin/dashboard layer, providing pre-paint anti-flash dark/light theme switching, biometric passkeys, dynamic data views (Kanban, Calendar, Cards, Table), tamper-evident audit logs, and media library manager.

### Integration with Filament
- **Filament** serves as the primary resource-rich engine for CRUD operations, multi-panel routing (`/superadmin`, `/app`, `/client`, `/partner`), form builders, and table builders.
- **`imrandevbd/laravel-admin-panel`** provides the shared client-side theme engine (`x-admin-panel`), custom Alpine widget wrappers, biometric passkey hooks, zero-build asset styling for marketing/embed pages, and dedicated views for dynamic Kanban and Calendar widgets.

---

## 3. `imrandevbd/laravel-ai-catalog` (Commerce Intelligence & Scraper)

### Package Overview
- **Packagist:** `imrandevbd/laravel-ai-catalog` (dev-main)
- **Repository:** `imranbru99/laravel-ai-catalog`
- **Namespace:** `ImranDevBd\AiCatalog\`
- **Service Provider:** `ImranDevBd\AiCatalog\AiCatalogServiceProvider`
- **Config Published:** `config/ai-catalog.php`
- **Role:** Autonomous e-commerce listing generator and competitor/supplier scraper with SSRF protection, Digital Product Passport (DPP), social commerce video storyboard generator (TikTok Shop/Reels/Shorts), return reduction intelligence, and strict `draft -> review -> approve` governance gate.

### Key Classes & APIs
- **Facade:** `ImranDevBd\AiCatalog\Facades\AiCatalog`
  - `AiCatalog::fromUrl(string $url)`: Safe competitor/supplier scrape with SSRF validation.
  - `AiCatalog::fromKeyword(string $keyword)`: Generates full product listing from keyword seed.
  - `AiCatalog::fromText(string $rawText)`: Extracts structured catalog data from unstructured manufacturer text.
  - `AiCatalog::fromPhoto(string|UploadedFile $image)`: Multimodal vision extraction.
  - `AiCatalog::fromDatasheet(string $filePath)`: Parses PDF/Markdown/CSV technical specs.
  - `AiCatalog::approve(AiCatalogDraft $draft)`: Persists approved listing into the application's `Product` model.
- **Configuration Mapping:** Configured in `config/ai-catalog.php` to bind directly to `App\Models\Product` and custom fields.

### SaaS Integration Plan
- Powers the **Scraper & Competitor Import** module and **Single/Bulk Generation Studio** draft flow.
- Scraper includes strict SSRF defenses (blocking `127.0.0.1`, `10.0.0.0/8`, `172.16.0.0/12`, `192.168.0.0/16`, AWS metadata `169.254.169.254`, and cloud internal endpoints).

---

## 4. `imrandevbd/laravel-ai-hub` (Unified Multi-Provider AI Gateway)

### Package Overview
- **Packagist:** `imrandevbd/laravel-ai-hub` (v1.7.3)
- **Repository:** `imranbru99/laravel-ai-hub`
- **Namespace:** `ImranDevBd\AiHub\`
- **Service Provider:** `ImranDevBd\AiHub\AiHubServiceProvider`
- **Config Published:** `config/ai-hub.php`
- **Studio UI Route:** `/ai-hub` (Playground, telemetry, budget controls)
- **Role:** The sole gateway for all AI inference. Supports 13 providers (OpenAI, Gemini, Claude, Grok, DeepSeek, Mistral, Ollama, OpenRouter, Azure, Together, Fireworks, Perplexity), priority failover chains, USD cost tracking per 1M tokens, hybrid thinking budgets, and full offline testing fake.

### Key Classes & APIs
- **Facade:** `ImranDevBd\AiHub\Facades\AIHub`
  - `AIHub::prompt(string $prompt)`: Fluent execution chain.
  - `AIHub::model(string $modelId)`: Route to specific LLM.
  - `AIHub::priority(array $providers)`: Dynamic fallback order.
  - `AIHub::fake(array $responses = [])`: Deterministic offline mock for CI, demos, and local runs.
  - `AIHub::chat(array $messages)`: Multi-turn conversational sessions.
  - `AIHub::streamResponse()`: Server-sent events (SSE) streaming.
  - `AIHub::structured(array $schema)`: JSON schema enforcement with depth-aware auto-repair.
- **Cost Tracking:** Embedded pricing tables calculate exact USD expenditures per token.

### SaaS Integration Plan
- Centralized in `App\Services\Ai\AiHubManagerService` with workspace BYOK (Bring Your Own Key) overrides, credit-to-USD rate limits, and fallback orchestration.
- The `Fake` driver is active by default when external keys are not provided, allowing 100% realistic offline demo usage without API charges.

---

## 5. `imrandevbd/laravel-attendance-hub` (Attendance Devices & HR Engine)

### Package Overview
- **Packagist:** `imrandevbd/laravel-attendance-hub` (dev-main)
- **Repository:** `imranbru99/laravel-attendance-hub`
- **Namespace:** `ImranDevBd\AttendanceHub\`
- **Service Provider:** `ImranDevBd\AttendanceHub\AttendanceHubServiceProvider`
- **Config Published:** `config/attendance-hub.php`
- **Role:** Hardware device registry (ZKTeco, Hikvision, Suprema, Dahua, ADMS, Wiegand, Virtual, Fake), push/pull punch sync, GDPR-compliant credential encryption, anti-passback debouncing, and shift/hours/lateness calculator for agency content teams.

### Key Classes & APIs
- **Facade:** `ImranDevBd\AttendanceHub\Facades\AttendanceHub`
  - `AttendanceHub::fake()`: Complete mock driver with assertions and failure simulations.
  - `AttendanceHub::driver('zkteco')->syncPunches($device)`: Real-time socket pull.
  - `AttendanceHub::admsListener()`: Built-in `/iclock/cdata` cloud push listener.
- **Calculator:** `ImranDevBd\AttendanceHub\Services\AttendanceCalculator`
  - Calculates first-in, last-out, late minutes, early departure, overtime, and daily presence status.
- **Commands:**
  - `php artisan attendance:sync`: Pulls logs from all active terminals.
  - `php artisan attendance:simulate`: Seeds realistic employee shifts and punch data for testing and demonstrations.

### SaaS Integration Plan
- Powering the optional **Team Attendance and HR** module for marketplace agencies managing on-site data entry operators and copywriters.
- Filament resource and dashboard widget embedded in the Workspace Panel when the module is activated.

---

## 6. `imrandevbd/laravel-unicode-pdf` (Multilingual Unicode PDF Generator)

### Package Overview
- **Packagist:** `imrandevbd/laravel-unicode-pdf` (dev-main)
- **Repository:** `imranbru99/laravel-unicode-pdf`
- **Namespace:** `ImranDev\UnicodePdf\`
- **Service Provider:** `ImranDev\UnicodePdf\UnicodePdfServiceProvider`
- **Config Published:** `config/unicode-pdf.php`
- **Fonts Published:** `storage/app/unicode-pdf/fonts`
- **Role:** Universal Unicode-first PDF generation across complex scripts: Bengali, Arabic (bi-directional RTL), Hindi (Devanagari conjunct shaping), Chinese/Japanese/Korean (CJK), and Latin scripts.

### Key Classes & APIs
- **Facade:** `ImranDev\UnicodePdf\Facades\UnicodePdf`
  - `UnicodePdf::preset('bengali')`: Typography presets for SolaimanLipi/Kalpurush.
  - `UnicodePdf::preset('arabic')`: RTL directionality with Amiri/Scheherazade.
  - `UnicodePdf::loadView(string $view, array $data)`: Loads Blade template.
  - `->a4()` / `->letter()` / `->orientation('landscape')`: Page sizing and layout.
  - `->nativeNumerals(bool $enable)`: Formats numbers into Bengali (১, ২, ৩) or Arabic-Indic (١, ٢, ٣).
  - `->download(string $filename)` / `->stream()` / `->save(string $path)`.

### SaaS Integration Plan
- Generates all printable documents:
  - Product Catalog Line Sheets
  - Listing Content Audit Reports
  - Client Approval Diffs
  - Billing Invoices & Receipts
  - HR Monthly Attendance Sheets
- Guarantees flawless rendering without font corruption or broken glyphs across international locales.

---

## 7. `imrandevbd/laravel-universal-slug` (Multilingual Universal Slug Generator)

### Package Overview
- **Packagist:** `imrandevbd/laravel-universal-slug` (dev-main)
- **Repository:** `imranbru99/laravel-universal-slug`
- **Namespace:** `ImranDev\UniversalSlug\`
- **Service Provider:** `ImranDev\UniversalSlug\UniversalSlugServiceProvider`
- **Config Published:** `config/universal-slug.php`
- **Migrations:** `slug_histories` table for automatic 301 redirects when slugs change.
- **Role:** Fast, Unicode-safe URL slug generator for any language (Bengali, Arabic, Hindi, CJK, Cyrillic, Latin) with zero `%20` spaces, collision suffixes, reserved route protection, and Eloquent model integration.

### Key Classes & APIs
- **Trait:** `ImranDev\UniversalSlug\Traits\HasUniversalSlug` (adds automatic slug generation to Eloquent models on save).
- **Attribute:** `#[Slug(from: 'name', target: 'slug')]` (PHP 8 attribute syntax).
- **Facade:** `ImranDev\UniversalSlug\Facades\UniversalSlug`
  - `UniversalSlug::generate(string $string, string $mode = 'unicode', string $separator = '-')`
  - `UniversalSlug::of($string)->locale('bn')->generate()`
- **Global Helper:** `universal_slug($string, $options)`

### SaaS Integration Plan
- Applied across all content models: `Product`, `Category`, `Brand`, `Collection`, `Page`, `BlogPost`, and `KnowledgeBaseArticle`.
- Generates beautiful native URLs in Bengali (e.g. `/products/ওয়্যারলেস-ইয়ারবাড`), Arabic (e.g. `/products/سماعات-بلوتوث`), and English (e.g. `/products/wireless-earbuds`) while automatically recording old slugs in `slug_histories` for SEO redirect preservation.

---

## Summary of Integration Matrix

| Package | Admin / Workspace Panel | AI & Catalog Engine | PDF & Public Output | SEO & URLs | Performance |
|---|:---:|:---:|:---:|:---:|:---:|
| `drutogoti` | Diagnostics & Speed Page | Telemetry on AI Queues | Monitored | Monitored | Core Profiler |
| `laravel-admin-panel` | Themes, Passkeys & Widgets | Layout Components | - | - | Anti-Flash UI |
| `laravel-ai-catalog` | Scraper & Import UI | 7 Input Modes & Review Flow | Catalog Mapping | Tag Enrichment | Rate-Safe Scrape |
| `laravel-ai-hub` | Model Routing & Budgets | Universal Gateway & Fake Mock | - | - | Zero-Worker Costing |
| `laravel-attendance-hub` | Devices & Punch Log Panel | - | Monthly Punch PDF | - | Shift Calculator |
| `laravel-unicode-pdf` | Download Buttons | - | Catalogs, Invoices, Reports | - | Cached Fonts |
| `laravel-universal-slug` | Dynamic Slug Fields | - | - | All Models & Handles | Fast In-Memory Engine |
