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
        Schema::create('templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workspace_id')->nullable()->constrained('workspaces')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->string('category')->default('general');
            $table->string('marketplace')->default('all');
            $table->text('system_prompt')->nullable();
            $table->longText('user_prompt_template');
            $table->json('variables')->nullable();
            $table->boolean('is_system')->default(false);
            $table->boolean('is_active')->default(true);
            $table->string('version')->default('1.0.0');
            $table->timestamps();

            $table->index(['workspace_id', 'slug']);
        });

        Schema::create('brand_voices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workspace_id')->constrained('workspaces')->cascadeOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained('brands')->nullOnDelete();
            $table->string('name');
            $table->string('tone')->default('professional'); // professional, conversational, luxurious, witty, urgent, technical, bold
            $table->string('reading_level')->default('standard'); // simple, standard, advanced
            $table->json('banned_words')->nullable();
            $table->json('required_words')->nullable();
            $table->text('style_examples')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        Schema::create('bulk_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workspace_id')->constrained('workspaces')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('template_id')->nullable()->constrained('templates')->nullOnDelete();
            $table->string('name');
            $table->string('marketplace')->default('generic');
            $table->string('locale', 10)->default('en');
            $table->string('status')->default('pending'); // pending, processing, paused, completed, failed, cancelled
            $table->unsignedInteger('total_items')->default(0);
            $table->unsignedInteger('processed_items')->default(0);
            $table->unsignedInteger('failed_items')->default(0);
            $table->decimal('estimated_cost', 8, 4)->default(0);
            $table->decimal('actual_cost', 8, 4)->default(0);
            $table->json('settings')->nullable();
            $table->json('error_log')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['workspace_id', 'status']);
        });

        Schema::create('bulk_job_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bulk_job_id')->constrained('bulk_jobs')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('generated_listing_id')->nullable()->constrained('product_listings')->nullOnDelete();
            $table->string('status')->default('pending'); // pending, processing, completed, failed
            $table->text('error_message')->nullable();
            $table->unsignedInteger('tokens_used')->default(0);
            $table->decimal('cost', 8, 4)->default(0);
            $table->timestamps();

            $table->index(['bulk_job_id', 'status']);
        });

        Schema::create('marketplace_rules', function (Blueprint $table) {
            $table->id();
            $table->string('marketplace_code')->unique(); // amazon, ebay, shopify, etc.
            $table->string('marketplace_name');
            $table->unsignedInteger('title_max_length')->default(200);
            $table->unsignedInteger('bullet_max_count')->default(5);
            $table->unsignedInteger('bullet_max_length')->default(500);
            $table->unsignedInteger('desc_max_length')->default(2000);
            $table->json('required_fields')->nullable();
            $table->json('forbidden_terms')->nullable();
            $table->json('rules_data')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('channel_connections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workspace_id')->constrained('workspaces')->cascadeOnDelete();
            $table->string('channel'); // shopify, woocommerce, amazon, ebay, custom_webhook
            $table->string('name');
            $table->text('credentials'); // encrypted JSON
            $table->json('field_mapping')->nullable();
            $table->json('settings')->nullable();
            $table->string('status')->default('connected'); // connected, error, paused
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamps();

            $table->index(['workspace_id', 'channel']);
        });

        Schema::create('channel_sync_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('channel_connection_id')->constrained('channel_connections')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->foreignId('product_listing_id')->nullable()->constrained('product_listings')->nullOnDelete();
            $table->string('action'); // push, pull, sync
            $table->string('status'); // success, error
            $table->json('payload')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
        });

        Schema::create('feeds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workspace_id')->constrained('workspaces')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->string('type')->default('google_merchant'); // google_merchant, meta_catalog, generic_xml, generic_csv
            $table->string('token', 64)->unique();
            $table->string('marketplace')->default('generic');
            $table->json('filters')->nullable();
            $table->string('file_path')->nullable();
            $table->unsignedInteger('item_count')->default(0);
            $table->timestamp('regenerated_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['workspace_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feeds');
        Schema::dropIfExists('channel_sync_logs');
        Schema::dropIfExists('channel_connections');
        Schema::dropIfExists('marketplace_rules');
        Schema::dropIfExists('bulk_job_items');
        Schema::dropIfExists('bulk_jobs');
        Schema::dropIfExists('brand_voices');
        Schema::dropIfExists('templates');
    }
};
