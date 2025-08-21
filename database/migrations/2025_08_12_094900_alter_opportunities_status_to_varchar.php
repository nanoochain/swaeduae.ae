<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('opportunities')) return;

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE `opportunities` MODIFY `status` VARCHAR(20) NULL");
            return;
        }

        if ($driver === 'pgsql') {
            DB::statement("ALTER TABLE opportunities ALTER COLUMN status TYPE VARCHAR(20)");
            DB::statement("ALTER TABLE opportunities ALTER COLUMN status DROP NOT NULL");
            return;
        }

        // sqlite / others: no-op (SQLite uses dynamic typing; VARCHAR behaves like TEXT)
        // Ensure the column exists; add if missing.
        Schema::table('opportunities', function (Blueprint $table) {
            if (! Schema::hasColumn('opportunities', 'status')) {
                $table->string('status', 20)->nullable();
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('opportunities')) return;

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            // Non-destructive rollback (keep as VARCHAR(20) NULL); adjust if you truly need old definition
            DB::statement("ALTER TABLE `opportunities` MODIFY `status` VARCHAR(20) NULL");
            return;
        }

        if ($driver === 'pgsql') {
            // Roll back to TEXT (keeping NULLs to avoid failures)
            DB::statement("ALTER TABLE opportunities ALTER COLUMN status TYPE TEXT");
            return;
        }

        // sqlite / others: no-op
    }
};
