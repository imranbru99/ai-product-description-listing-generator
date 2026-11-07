<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workspace_id')->constrained('workspaces')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('taxonomy_code')->nullable();
            $table->timestamps();

            $table->index(['workspace_id', 'slug']);
        });

        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workspace_id')->constrained('workspaces')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->string('logo')->nullable();
            $table->string('website')->nullable();
            $table->text('voice_guidelines')->nullable();
            $table->timestamps();

            $table->index(['workspace_id', 'slug']);
        });

        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workspace_id')->constrained('workspaces')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['workspace_id', 'slug']);
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workspace_id')->constrained('workspaces')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained('brands')->nullOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->string('sku')->nullable();
            $table->string('barcode')->nullable();
            $table->string('status')->default('active'); // draft, active, archived
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('cost_price', 10, 2)->nullable();
            $table->string('currency', 3)->default('USD');
            $table->json('tags')->nullable();
            $table->json('attributes')->nullable();
            $table->json('custom_fields')->nullable();
            $table->string('source_url')->nullable();
            $table->timestamp('last_scraped_at')->nullable();
            $table->timestamps();

            $table->index(['workspace_id', 'slug']);
            $table->index(['workspace_id', 'sku']);
            $table->index(['workspace_id', 'status']);
        });

        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('title');
            $table->string('sku')->nullable();
            $table->string('barcode')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->integer('inventory_count')->default(0);
            $table->json('option_values')->nullable(); // e.g. {"Size": "L", "Color": "Blue"}
            $table->timestamps();
        });

        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('path');
            $table->string('url')->nullable();
            $table->string('alt_text')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });

        Schema::create('product_listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('workspace_id')->constrained('workspaces')->cascadeOnDelete();
            $table->string('marketplace')->default('generic'); // amazon, ebay, shopify, etsy, walmart, etc.
            $table->string('locale', 10)->default('en');
            $table->string('title');
            $table->text('short_description')->nullable();
            $table->longText('long_description')->nullable();
            $table->json('bullet_points')->nullable();
            $table->json('search_terms')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->json('faqs')->nullable();
            $table->json('specifications')->nullable();
            $table->text('care_instructions')->nullable();
            $table->text('whats_in_the_box')->nullable();
            $table->json('ad_copy')->nullable();
            $table->json('dpp_data')->nullable(); // Digital Product Passport (EU 2027 Ecodesign)
            $table->json('social_copy')->nullable(); // TikTok/Reels hooks & scripts
            $table->string('status')->default('draft'); // draft, in_review, approved, published, rejected
            $table->integer('version')->default(1);
            $table->integer('seo_score')->default(85);
            $table->integer('quality_score')->default(90);
            $table->string('compliance_status')->default('passed'); // passed, warning, failed
            $table->json('compliance_flags')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['workspace_id', 'marketplace', 'locale']);
            $table->index(['workspace_id', 'status']);
        });

        Schema::create('listing_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_listing_id')->constrained('product_listings')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->integer('version_number');
            $table->string('title');
            $table->text('short_description')->nullable();
            $table->longText('long_description')->nullable();
            $table->json('bullet_points')->nullable();
            $table->text('change_summary')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listing_versions');
        Schema::dropIfExists('product_listings');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('products');
        Schema::dropIfExists('collections');
        Schema::dropIfExists('brands');
        Schema::dropIfExists('categories');
    }
};
