<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('volunteer_hours')) {
            Schema::table('volunteer_hours', function (Blueprint $table) {
                if (!Schema::hasColumn('volunteer_hours', 'minutes')) {
                    $table->integer('minutes')->default(0)->after('hours');
                }
            });

            // Backfill if an 'hours' column exists
            if (Schema::hasColumn('volunteer_hours', 'hours')) {
                DB::statement("UPDATE volunteer_hours SET minutes = COALESCE(minutes, 0) + ROUND(hours * 60) WHERE minutes = 0");
            }
        }
    }

    public function down(): void
    {
        // keep data; non-destructive
    }
};
