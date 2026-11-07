<p align="center">
  <img src="docs/screenshots/dashboard.svg" alt="AI Product Listing Generator dashboard preview" width="100%"/>
</p>

<h1 align="center">AI Product Listing Generator</h1>

<p align="center"><strong>Bulk marketplace titles, descriptions and SEO content</strong></p>

<p align="center">
  <a href="https://github.com/imranbru99/ai-product-description-listing-generator"><img alt="GitHub" src="https://img.shields.io/badge/GitHub-imranbru99%2Fai-product-description-listing-generator-0F172A?style=for-the-badge&logo=github"/></a>
  <img alt="Laravel" src="https://img.shields.io/badge/Laravel-PHP-FF2D20?style=for-the-badge&logo=laravel&logoColor=white"/>
  <img alt="Author" src="https://img.shields.io/badge/Author-Imran%20Ahmed-14B8A6?style=for-the-badge"/>
</p>

---

## Overview

**AI Product Listing Generator** is a complete business application focused on real operational workflows. This README lists the full feature surface so GitHub visitors and search engines can understand every capability of the product.

## Product preview

| Dashboard | Operations |
| :---: | :---: |
| ![Dashboard](docs/screenshots/dashboard.svg) | ![Operations](docs/screenshots/operations.svg) |

## Complete feature list

### Product modules & capabilities

- Everything user-facing must be translatable
- Attendance machines
- Power the optional Team Attendance and HR module
- Build a Module Manager so every feature is a self-contained module
- Workspace-based tenancy
- Use spatie/laravel-permission
- Platform Staff Panel
- Workspace Panel
- Client Portal
- Reseller/Affiliate Portal
- Public website
- Core
- AI content engine
- Image-to-Content
- Marketplace and channels
- Client Approval Portal
- CRM and Leads
- Team Attendance and HR
- Features overview plus one dedicated page per module/feature group
- Marketplaces and Integrations directory
- Accessible

### Admin / Filament resources

- Brand Voice
- Bulk Job
- Client Listing
- Feed
- Marketplace Rule
- Module
- Partner Payout
- Partner Referral
- Plan
- Product Listing
- Product
- Template
- User
- Workspace

### Domain models

- Affiliate
- Affiliate Payout
- Affiliate Referral
- Announcement
- Article
- Article Category
- Audit Log
- Brand
- Brand Voice
- Bulk Job
- Bulk Job Item
- Category
- Channel Connection
- Channel Sync Log
- Collection
- Comment
- Credit Transaction
- Feature Flag
- Feed
- Integration
- Invoice
- Lead
- Listing Review
- Listing Version
- Marketplace Rule
- Module
- Plan
- Product
- Product Image
- Product Listing
- Product Variant
- Project
- Setting
- Task
- Template
- Ticket
- Ticket Reply
- Webhook
- Webhook Delivery
- Workspace
- Workspace Module

### HTTP / API controllers

- Public Site APIs & screens

### Detailed feature requirements delivered

- MASTER PROMPT: Build "AI Product Description and Listing Generator: Bulk Marketplace Content" (End-to-End SaaS, Laravel + Filament)
- PRODUCT IDENTITY
- Product name: AI Product Description and Listing Generator: Bulk Marketplace Content
- Short name / brand: use a configurable APP_NAME and a brand settings group so the name, logo, colors and domain can be changed from the admin panel (white-label ready).
- Target customers: e-commerce sellers, marketplace agencies, dropshippers, brands, manufacturers, wholesalers, content teams, freelancers, resellers.
- TECH STACK AND HARD RULES
- Database: MySQL 8 (primary). Redis for cache, queues, sessions and rate limits when available, with graceful fallback to database/file drivers.
- Frontend for public site: Blade + Livewire + Alpine + Tailwind (no heavy SPA required). Dashboards are Filament.
- Queue: Laravel Horizon-compatible queues, with database as the default fallback so it runs on cheap hosting. Scheduler for recurring jobs.
- Testing: Pest (or PHPUnit). Static analysis: Larastan level 6+. Code style: Laravel Pint.
- REQUIRED PACKAGES (install and integrate all of them meaningfully)
- All PDF output: listing catalogs, product sheets, invoices, reports, content audit reports, attendance sheets. Must render Bengali, Arabic, Hindi, CJK, etc. correctly.
- All slugs: products, categories, public pages, blog posts, marketplace URL handles, in every language.
- ARCHITECTURE
- Module registry table: modules (key, name, description, version, category, is_core, dependencies, default_enabled).
- Per-workspace switches: workspace_modules (workspace_id, module_key, enabled, settings JSON, enabled_at, enabled_by).
- Core modules cannot be disabled: Auth, Workspaces, Users and Roles, Settings, Billing basics, Audit log, Module Manager.
- Agencies: a workspace can have Client Workspaces/Brands beneath it (agency to client hierarchy) with scoped access and client-facing approval portals.
- Custom domain and subdomain support per workspace for white-label portals (domain mapping table plus middleware).
- Platform Staff Panel (same panel, limited roles): Support Agent, Finance Manager, Content Moderator, Sales/CRM Manager.
- Workspace Panel (/app): for business users, with role-based menus:
- Client Portal (/client): external clients of agencies: view listings, approve/reject with comments, download reports, see invoices.
- Reseller/Affiliate Portal (/partner): referral links, commissions, payouts, white-label sub-accounts.
- COMPLETE MODULE LIST (build ALL; each toggleable per section 3.1)
- Settings Center: general, brand, localization, email, storage, queues, security, AI defaults.
- Audit Log and Activity Feed: who did what, filterable, exportable.
- Notifications: in-app, email, Slack/Telegram/WhatsApp-webhook, push-ready; per-user preferences.
- Onboarding Wizard: business type, preset, first product, first AI generation in under 3 minutes.
- Media Library: upload, folders, image optimization, background tasks, CDN-ready, alt-text bulk generation.
- Brand Voice and Style Guides: define tone, banned/required words, reading level, examples; apply per brand/client; train from existing listings.
- Image-to-Content (Vision): generate descriptions, attributes and alt text from product images.
- Attribute Extraction and Enrichment: AI fills missing attributes, categories and taxonomy mapping.
- SEO and Keyword Module: keyword lists, density, title length, search-term optimizer, SEO score, schema.org JSON-LD generation, readability scores.
- Content Variants and A/B Testing: multiple versions per listing, performance tracking input, pick winner.
- AI Provider and Model Routing Settings (via laravel-ai-hub): per-task model, fallbacks, BYOK (bring your own key), usage limits, cost dashboard.
- Feed Generator: Google Merchant, Facebook/Meta catalog, and generic XML/CSV feeds with scheduled regeneration and public secure feed URLs.
- Review and Approval Workflow: configurable stages (Draft, In Review, Approved, Published), assignments, comments, change requests, side-by-side diff, SLAs, bulk approve.
- Version History and Rollback: every field change versioned, compare, restore.
- Tasks and Projects: content projects, assignments, due dates, Kanban and list views, workload view.
- Comments and Mentions: threaded, on any listing/field.
- Client Approval Portal (agency feature).
- Usage and Cost Analytics: tokens, credits, per user/project/client/model; budget alerts; margin reports for agencies.
- Analytics and Reports: content volume, quality scores over time, turnaround time, marketplace coverage, team productivity, ROI; export CSV/PDF.
- CRM and Leads (for the platform owner and for agencies): leads from public forms, pipeline, notes, follow-ups, conversion to workspace.
- Support Desk: tickets, categories, SLA, canned replies, live chat widget, email piping, satisfaction rating.
- Knowledge Base and Blog CMS: multilingual articles, categories, SEO, search; powers the public help center and blog.
- Affiliate and Reseller Program: referral tracking, tiered commissions, payout requests, payouts report, white-label reseller sub-accounts.
- Document and PDF Center (via laravel-unicode-pdf): product catalogs and line sheets in any language, listing audit reports, invoices, attendance sheets, white-label branded.
- Public REST API (v1): token auth (Sanctum), scoped abilities, rate limits, OpenAPI/Swagger docs auto-generated, versioning, idempotency keys, bulk endpoints.
- Webhooks: outgoing events with signing, retries, delivery log, manual replay; incoming webhooks for connectors.
- Integrations Hub: Zapier/Make-ready triggers and actions, Slack, Telegram, Google Sheets, Google Drive, Dropbox, S3-compatible storage.
- White-Label and Custom Domains: per-workspace branding, domain mapping, email from-address, custom login page.
- Feature Flags and Announcements: gradual rollout, in-app changelog and banners.
- System Health and Performance: queue status, failed jobs, scheduler heartbeat, storage, error log viewer, DrutoGoti status.
- Localization Manager: edit translations from the UI, add languages, import/export, missing-key detector.
- PUBLIC WEBSITE (must showcase EVERY feature in the README)
- Home: hero with live interactive demo generator (works with the Fake driver, no login), social proof, module overview, pricing teaser, testimonials, FAQ, CTA.
- Solutions pages by business type: Solo Seller, Dropshipper, Brand, Agency, Enterprise.
- Pricing: monthly/annual toggle, plan comparison matrix generated from modules, credit pack store, FAQ, enterprise contact form.
- Live Demo: "Try as demo user" buttons that log into pre-seeded demo accounts (read-only or auto-resetting sandbox).
- DEMO DATA AND DEMO USERS (mandatory, full end-to-end)
- Create DemoSeeder (and php artisan demo:reset, plus a scheduled auto-reset for the public sandbox) that produces a fully populated realistic business:
- Platform: 1 Super Admin, 1 Support Agent, 1 Finance Manager, plans (Free, Starter, Pro, Business, Enterprise, Agency), all modules registered.
- UX AND QUALITY REQUIREMENTS
- Dashboards per role with meaningful widgets (credits left, listings generated, approval queue, quality score trend, cost per listing, team workload).
- Performance targets: public pages under 1s TTFB with caching; bulk jobs chunked and memory-safe; N+1 queries eliminated (enable preventLazyLoading in non-production).
- Zero-downtime-friendly: queue-safe, idempotent jobs, retries with backoff, failed-job UI.
- DELIVERY PLAN (execute in this order; commit after each phase; run tests after each)
- Foundation: Laravel install, packages, config, auth, workspaces, roles/permissions, module manager, settings, audit log, Fake AI driver, CI config.
- Catalog and Import/Export: products, media, import center, scraper.

### Platform capabilities

- Multi-role ready structure for operators, managers and admins
- Database migrations and seeders for demo-ready local setup
- Laravel architecture with services/controllers/models separation
- Responsive UI foundation (Blade / Filament where enabled)
- Environment-based config for local and production databases
- GitHub-ready documentation with visual product previews

## Tech stack

- PHP 8.2+ / Laravel
- Filament admin resources (where enabled)
- Vite asset pipeline
- SQLite/MySQL-ready database layer
- Migrations, seeders, and test-ready structure

## Quick start

```bash
git clone https://github.com/imranbru99/ai-product-description-listing-generator.git
cd ai-product-description-listing-generator
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install && npm run build
php artisan serve
```

## Repository

- GitHub: https://github.com/imranbru99/ai-product-description-listing-generator
- Developer: **Imran Ahmed** · https://imrandev.bd

---

## 🤝 Let's Connect & Collaborate

I am open to **Senior Remote Full-Stack Roles**, AI Platform Architecture Contracts, and Enterprise Technical Advisory.

**Timezone:** UTC+6 (Dhaka / Rangpur, Bangladesh) — flexible overlap with US, UK & EU hours.  
**Delivery:** Async-ready · Slack · Discord · Jira · GitHub · production-first accountability.

| Channel | Handle | Link |
| :--- | :--- | :--- |
| Portfolio | imrandev.bd | https://imrandev.bd |
| Packagist | imrandevbd | https://packagist.org/packages/imrandevbd/ |
| LinkedIn | imranbru99 | https://linkedin.com/in/imranbru99 |
| GitHub | imranbru99 | https://github.com/imranbru99 |
| WhatsApp | +880 1576-918420 | https://wa.me/8801576918420 |
| Email | me@imrandev.bd | mailto:me@imrandev.bd |
| X | @imrandev_bd | https://x.com/imrandev_bd |
| YouTube | @ImranDevBD | https://youtube.com/@ImranDevBD |
