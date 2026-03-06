<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('login_attempts', function (Blueprint $table) {
            if (!Schema::hasColumn('login_attempts', 'guard')) {
                $table->string('guard', 20)->default('web')->after('email');
                $table->index(['guard', 'ip_address', 'created_at']);
            }
        });
    }

    public function down(): void
    {
        Schema::table('login_attempts', function (Blueprint $table) {
            if (Schema::hasColumn('login_attempts', 'guard')) {
                $table->dropIndex(['guard', 'ip_address', 'created_at']);
                $table->dropColumn('guard');
            }
        });
    }
};
