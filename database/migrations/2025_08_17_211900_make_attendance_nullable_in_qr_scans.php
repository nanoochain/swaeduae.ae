<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (Schema::hasTable('qr_scans')) {
            if (Schema::hasColumn('qr_scans','attendance_id')) {
                // allow missing attendance reference for generic scans
                DB::statement("ALTER TABLE `qr_scans` MODIFY `attendance_id` BIGINT UNSIGNED NULL");
            }
            // old schema had ip_address/user_agent as required; make them nullable
            if (Schema::hasColumn('qr_scans','ip_address')) {
                DB::statement("ALTER TABLE `qr_scans` MODIFY `ip_address` varchar(45) NULL");
            }
            if (Schema::hasColumn('qr_scans','user_agent')) {
                DB::statement("ALTER TABLE `qr_scans` MODIFY `user_agent` text NULL");
            }
            // ensure our `ip` column is permissive too
            if (Schema::hasColumn('qr_scans','ip')) {
                DB::statement("ALTER TABLE `qr_scans` MODIFY `ip` varchar(45) NULL");
            }
        }
    }
    public function down(): void { /* no-op for safety */ }
};
