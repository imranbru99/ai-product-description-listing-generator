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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('current_workspace_id')->nullable()->constrained('workspaces')->nullOnDelete();
            $table->string('avatar')->nullable();
            $table->string('locale', 10)->default('en');
            $table->string('timezone')->default('UTC');
            $table->text('two_factor_secret')->nullable();
            $table->timestamp('two_factor_confirmed_at')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('is_platform_admin')->default(false);
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip', 45)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['current_workspace_id']);
            $table->dropColumn([
                'current_workspace_id',
                'avatar',
                'locale',
                'timezone',
                'two_factor_secret',
                'two_factor_confirmed_at',
                'phone',
                'is_platform_admin',
                'last_login_at',
                'last_login_ip',
            ]);
        });
    }
};
