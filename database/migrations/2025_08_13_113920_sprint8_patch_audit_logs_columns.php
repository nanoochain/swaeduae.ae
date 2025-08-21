<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('audit_logs')) return;

        // Add columns only if missing (safe to run multiple times)
        if (!Schema::hasColumn('audit_logs','role')) {
            Schema::table('audit_logs', function (Blueprint $t) { $t->string('role',64)->nullable(); });
        }
        if (!Schema::hasColumn('audit_logs','method')) {
            Schema::table('audit_logs', function (Blueprint $t) { $t->string('method',10)->nullable(); });
        }
        if (!Schema::hasColumn('audit_logs','route')) {
            Schema::table('audit_logs', function (Blueprint $t) { $t->string('route',190)->nullable(); });
        }
        if (!Schema::hasColumn('audit_logs','route_name')) {
            Schema::table('audit_logs', function (Blueprint $t) { $t->string('route_name',190)->nullable(); });
        }
        if (!Schema::hasColumn('audit_logs','ip')) {
            Schema::table('audit_logs', function (Blueprint $t) { $t->string('ip',64)->nullable(); });
        }
        if (!Schema::hasColumn('audit_logs','user_agent')) {
            Schema::table('audit_logs', function (Blueprint $t) { $t->string('user_agent',255)->nullable(); });
        }
        if (!Schema::hasColumn('audit_logs','payload_excerpt')) {
            Schema::table('audit_logs', function (Blueprint $t) { $t->text('payload_excerpt')->nullable(); });
        }
    }
    public function down(): void {
        // No destructive down() needed for a patch migration.
    }
};
