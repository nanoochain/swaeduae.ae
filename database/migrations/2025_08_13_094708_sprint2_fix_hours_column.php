<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('volunteer_hours') && Schema::hasColumn('volunteer_hours','hours')) {
            // Make hours NOT NULL DEFAULT 0 (no DBAL needed)
            try { DB::statement("ALTER TABLE volunteer_hours MODIFY hours INT NOT NULL DEFAULT 0"); } catch (\Throwable $e) {}
            // Backfill any NULLs to 0
            try { DB::statement("UPDATE volunteer_hours SET hours = 0 WHERE hours IS NULL"); } catch (\Throwable $e) {}
        }
    }
    public function down(): void { /* keep safe */ }
};
