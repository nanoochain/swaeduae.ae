<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('volunteer_hours') && Schema::hasColumn('volunteer_hours','event_id')) {
            // Make event_id NULLable (keeps FK, but NULL is allowed)
            try { DB::statement("ALTER TABLE volunteer_hours MODIFY event_id BIGINT UNSIGNED NULL"); } catch (\Throwable $e) {}
        }
    }
    public function down(): void { /* no-op (safe) */ }
};
