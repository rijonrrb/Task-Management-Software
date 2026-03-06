<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('support_tickets', function (Blueprint $table) {
            if (Schema::hasColumn('support_tickets', 'assigned_to')) {
                $table->dropForeign(['assigned_to']);
                $table->dropColumn('assigned_to');
            }

            if (!Schema::hasColumn('support_tickets', 'assigned_admin_id')) {
                $table->foreignId('assigned_admin_id')->nullable()->after('ticket_number')->constrained('admins')->nullOnDelete();
            }
        });

        Schema::table('ticket_messages', function (Blueprint $table) {
            if (!Schema::hasColumn('ticket_messages', 'admin_id')) {
                $table->foreignId('admin_id')->nullable()->after('user_id')->constrained('admins')->nullOnDelete();
            }

            $table->foreignId('user_id')->nullable()->change();
        });

        Schema::table('custom_pages', function (Blueprint $table) {
            if (Schema::hasColumn('custom_pages', 'created_by')) {
                $table->dropForeign(['created_by']);
                $table->renameColumn('created_by', 'created_by_admin_id');
            }

            $table->foreign('created_by_admin_id')->references('id')->on('admins')->nullOnDelete();
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'is_admin')) {
                $table->dropColumn('is_admin');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'is_admin')) {
                $table->boolean('is_admin')->default(false)->after('password');
            }
        });

        Schema::table('custom_pages', function (Blueprint $table) {
            $table->dropForeign(['created_by_admin_id']);
            $table->renameColumn('created_by_admin_id', 'created_by');
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
        });

        Schema::table('ticket_messages', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);
            $table->dropColumn('admin_id');
            $table->foreignId('user_id')->nullable(false)->change();
        });

        Schema::table('support_tickets', function (Blueprint $table) {
            $table->dropForeign(['assigned_admin_id']);
            $table->dropColumn('assigned_admin_id');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
        });
    }
};
