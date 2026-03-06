<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false)->after('password');
            $table->boolean('is_banned')->default(false)->after('is_admin');
            $table->string('ban_reason')->nullable()->after('is_banned');
            $table->timestamp('banned_at')->nullable()->after('ban_reason');
            $table->string('last_ip', 45)->nullable()->after('banned_at');
            $table->text('last_user_agent')->nullable()->after('last_ip');
            $table->timestamp('last_login_at')->nullable()->after('last_user_agent');
            $table->integer('login_count')->default(0)->after('last_login_at');
            $table->integer('failed_login_attempts')->default(0)->after('login_count');
            $table->timestamp('locked_until')->nullable()->after('failed_login_attempts');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'is_admin', 'is_banned', 'ban_reason', 'banned_at',
                'last_ip', 'last_user_agent', 'last_login_at',
                'login_count', 'failed_login_attempts', 'locked_until',
            ]);
        });
    }
};
