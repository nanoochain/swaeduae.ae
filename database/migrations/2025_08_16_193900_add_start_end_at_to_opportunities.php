<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('opportunities', function (Blueprint $table) {
            if (!Schema::hasColumn('opportunities', 'start_at')) {
                $table->dateTime('start_at')->nullable()->index()->after('organization_id');
            }
            if (!Schema::hasColumn('opportunities', 'end_at')) {
                $table->dateTime('end_at')->nullable()->index()->after('start_at');
            }
        });

        // Backfill heuristics — only run statements if source columns exist.
        // 1) If 'starts_at' exists (old naming), copy it.
        if (Schema::hasColumn('opportunities', 'starts_at')) {
            DB::statement('UPDATE opportunities SET start_at = starts_at WHERE start_at IS NULL');
        }
        if (Schema::hasColumn('opportunities', 'ends_at')) {
            DB::statement('UPDATE opportunities SET end_at = ends_at WHERE end_at IS NULL');
        }

        // 2) If (start_date, start_time) exist, combine.
        if (Schema::hasColumn('opportunities', 'start_date') && Schema::hasColumn('opportunities', 'start_time')) {
            DB::statement("UPDATE opportunities
                           SET start_at = STR_TO_DATE(CONCAT(start_date, ' ', start_time), '%Y-%m-%d %H:%i:%s')
                           WHERE start_at IS NULL AND start_date IS NOT NULL AND start_time IS NOT NULL");
        }

        // 3) If (event_date, event_time) exist, combine.
        if (Schema::hasColumn('opportunities', 'event_date') && Schema::hasColumn('opportunities', 'event_time')) {
            DB::statement("UPDATE opportunities
                           SET start_at = STR_TO_DATE(CONCAT(event_date, ' ', event_time), '%Y-%m-%d %H:%i:%s')
                           WHERE start_at IS NULL AND event_date IS NOT NULL AND event_time IS NOT NULL");
        }

        // 4) If only 'start_date' exists, set to midnight.
        if (Schema::hasColumn('opportunities', 'start_date')) {
            DB::statement("UPDATE opportunities
                           SET start_at = STR_TO_DATE(CONCAT(start_date, ' 00:00:00'), '%Y-%m-%d %H:%i:%s')
                           WHERE start_at IS NULL AND start_date IS NOT NULL");
        }

        // 5) If only 'event_date' exists, set to midnight.
        if (Schema::hasColumn('opportunities', 'event_date')) {
            DB::statement("UPDATE opportunities
                           SET start_at = STR_TO_DATE(CONCAT(event_date, ' 00:00:00'), '%Y-%m-%d %H:%i:%s')
                           WHERE start_at IS NULL AND event_date IS NOT NULL");
        }
    }

    public function down(): void
    {
        Schema::table('opportunities', function (Blueprint $table) {
            if (Schema::hasColumn('opportunities', 'end_at')) {
                $table->dropIndex(['end_at']);
                $table->dropColumn('end_at');
            }
            if (Schema::hasColumn('opportunities', 'start_at')) {
                $table->dropIndex(['start_at']);
                $table->dropColumn('start_at');
            }
        });
    }
};
