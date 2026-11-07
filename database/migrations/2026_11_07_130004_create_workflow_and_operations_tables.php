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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workspace_id')->constrained('workspaces')->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('status')->default('active'); // active, completed, on_hold, cancelled
            $table->date('due_date')->nullable();
            $table->timestamps();

            $table->index(['workspace_id', 'status']);
        });

        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
            $table->foreignId('workspace_id')->constrained('workspaces')->cascadeOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('status')->default('todo'); // todo, in_progress, review, done
            $table->string('priority')->default('medium'); // low, medium, high, urgent
            $table->date('due_date')->nullable();
            $table->timestamps();

            $table->index(['workspace_id', 'status']);
            $table->index(['workspace_id', 'assigned_to']);
        });

        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workspace_id')->constrained('workspaces')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->morphs('commentable'); // listing, product, task, etc.
            $table->foreignId('parent_id')->nullable()->constrained('comments')->cascadeOnDelete();
            $table->text('content');
            $table->timestamps();

            $table->index(['workspace_id', 'created_at']);
        });

        Schema::create('listing_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_listing_id')->constrained('product_listings')->cascadeOnDelete();
            $table->foreignId('workspace_id')->constrained('workspaces')->cascadeOnDelete();
            $table->foreignId('reviewer_id')->constrained('users')->cascadeOnDelete();
            $table->string('status'); // approved, rejected, change_requested
            $table->text('feedback')->nullable();
            $table->timestamps();
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workspace_id')->constrained('workspaces')->cascadeOnDelete();
            $table->string('invoice_number')->unique();
            $table->string('status')->default('pending'); // paid, pending, failed, refunded
            $table->decimal('amount', 10, 2);
            $table->decimal('tax', 10, 2)->default(0);
            $table->string('currency', 3)->default('USD');
            $table->json('line_items');
            $table->string('payment_gateway')->default('stripe'); // stripe, paypal, sslcommerz, bkash, nagad, manual, fake
            $table->string('transaction_id')->nullable();
            $table->string('pdf_path')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['workspace_id', 'status']);
        });

        Schema::create('credit_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workspace_id')->constrained('workspaces')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('type'); // credit, debit
            $table->integer('amount');
            $table->integer('balance_after');
            $table->string('description');
            $table->nullableMorphs('reference');
            $table->timestamp('created_at')->useCurrent();

            $table->index(['workspace_id', 'created_at']);
        });

        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workspace_id')->nullable()->constrained('workspaces')->nullOnDelete();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->string('status')->default('new'); // new, contacted, qualified, converted, lost
            $table->string('source')->default('website_contact');
            $table->decimal('estimated_value', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workspace_id')->nullable()->constrained('workspaces')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('ticket_number')->unique();
            $table->string('subject');
            $table->string('priority')->default('medium'); // low, medium, high, urgent
            $table->string('status')->default('open'); // open, pending, resolved, closed
            $table->string('category')->default('general');
            $table->timestamps();

            $table->index(['status', 'priority']);
        });

        Schema::create('ticket_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('tickets')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('message');
            $table->boolean('is_internal')->default(false);
            $table->json('attachments')->nullable();
            $table->timestamps();
        });

        Schema::create('article_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('type')->default('blog'); // blog, knowledge_base
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('article_categories')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('locale', 10)->default('en');
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('status')->default('published'); // draft, published
            $table->unsignedInteger('views_count')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();

            $table->index(['status', 'locale']);
        });

        Schema::create('affiliates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('referral_code', 32)->unique();
            $table->decimal('commission_rate', 5, 2)->default(20.00); // 20%
            $table->decimal('balance', 10, 2)->default(0);
            $table->decimal('total_earned', 10, 2)->default(0);
            $table->text('payment_details')->nullable(); // encrypted
            $table->string('status')->default('active'); // active, pending, suspended
            $table->timestamps();
        });

        Schema::create('affiliate_referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('affiliate_id')->constrained('affiliates')->cascadeOnDelete();
            $table->foreignId('referred_workspace_id')->constrained('workspaces')->cascadeOnDelete();
            $table->decimal('commission_amount', 10, 2)->default(0);
            $table->string('status')->default('pending'); // pending, approved, paid
            $table->timestamps();
        });

        Schema::create('affiliate_payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('affiliate_id')->constrained('affiliates')->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->string('status')->default('pending'); // pending, approved, paid, rejected
            $table->string('payment_method')->default('paypal');
            $table->text('notes')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliate_payouts');
        Schema::dropIfExists('affiliate_referrals');
        Schema::dropIfExists('affiliates');
        Schema::dropIfExists('articles');
        Schema::dropIfExists('article_categories');
        Schema::dropIfExists('ticket_replies');
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('leads');
        Schema::dropIfExists('credit_transactions');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('listing_reviews');
        Schema::dropIfExists('comments');
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('projects');
    }
};
