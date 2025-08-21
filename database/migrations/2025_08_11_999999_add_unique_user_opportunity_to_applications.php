<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('applications')) {
            return;
        }

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'sqlite') {
            // SQLite: use PRAGMA-compatible IF NOT EXISTS
            DB::statement('CREATE UNIQUE INDEX IF NOT EXISTS applications_user_opportunity_unique ON applications (user_id, opportunity_id)');
            return;
        }

        if ($driver === 'pgsql') {
            // Postgres supports IF NOT EXISTS
            DB::statement('CREATE UNIQUE INDEX IF NOT EXISTS applications_user_opportunity_unique ON applications (user_id, opportunity_id)');
            return;
        }

        if ($driver === 'mysql') {
            // MySQL: check via SHOW INDEX, then add once
            $exists = collect(DB::select("SHOW INDEX FROM applications WHERE Key_name = 'applications_user_opportunity_unique'"))->isNotEmpty();
            if (! $exists) {
                Schema::table('applications', function (Blueprint $table) {
                    $table->unique(['user_id','opportunity_id'], 'applications_user_opportunity_unique');
                });
            }
            return;
        }

        // Fallback for any other drivers
        Schema::table('applications', function (Blueprint $table) {
            $table->unique(['user_id','opportunity_id'], 'applications_user_opportunity_unique');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('applications')) {
            return;
        }

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'sqlite') {
            DB::statement('DROP INDEX IF EXISTS applications_user_opportunity_unique');
            return;
        }

        if ($driver === 'pgsql') {
            DB::statement('DROP INDEX IF EXISTS applications_user_opportunity_unique');
            return;
        }

        if ($driver === 'mysql') {
            Schema::table('applications', function (Blueprint $table) {
                $table->dropUnique('applications_user_opportunity_unique');
            });
            return;
        }
    }
};
