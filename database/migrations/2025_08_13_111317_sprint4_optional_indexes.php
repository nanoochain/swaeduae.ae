<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        if (Schema::hasTable('opportunities')) {
            try { DB::statement("CREATE INDEX IF NOT EXISTS idx_opps_region ON opportunities (region)"); } catch (\Throwable $e) {}
            if (Schema::hasColumn('opportunities','title')) {
                try { DB::statement("CREATE INDEX IF NOT EXISTS idx_opps_title ON opportunities (title)"); } catch (\Throwable $e) {}
            }
            if (Schema::hasColumn('opportunities','category_id')) {
                try { DB::statement("CREATE INDEX IF NOT EXISTS idx_opps_category ON opportunities (category_id)"); } catch (\Throwable $e) {}
            }
        }
    }
    public function down(): void {}
};
