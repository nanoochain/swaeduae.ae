<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('certificates')) return;

        $driver = Schema::getConnection()->getDriverName();

        // ensure the column exists at all
        Schema::table('certificates', function (Blueprint $t) {
            if (! Schema::hasColumn('certificates', 'hours')) {
                // create if missing; this is portable
                $t->decimal('hours', 8, 2)->default(0);
            }
        });

        if ($driver === 'mysql') {
            // MySQL: OK to MODIFY
            DB::statement("ALTER TABLE `certificates` MODIFY `hours` DECIMAL(8,2) NOT NULL DEFAULT 0.00");
            return;
        }

        if ($driver === 'pgsql') {
            // Postgres: ALTER TYPE + defaults/nullability
            DB::statement("ALTER TABLE certificates ALTER COLUMN hours TYPE NUMERIC(8,2)");
            DB::statement("ALTER TABLE certificates ALTER COLUMN hours SET DEFAULT 0.00");
            DB::statement("UPDATE certificates SET hours = 0.00 WHERE hours IS NULL");
            DB::statement("ALTER TABLE certificates ALTER COLUMN hours SET NOT NULL");
            return;
        }

        // SQLite / others:
        // No "MODIFY" support; treat as no-op aside from making sure NULLs are filled.
        DB::statement("UPDATE certificates SET hours = 0.00 WHERE hours IS NULL");
        // (If you truly need NOT NULL on SQLite, you'd need a table-rebuild migration.)
    }

    public function down(): void
    {
        if (! Schema::hasTable('certificates')) return;

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            // keep it simple/non-destructive
            DB::statement("ALTER TABLE `certificates` MODIFY `hours` DECIMAL(8,2) NULL DEFAULT NULL");
            return;
        }

        if ($driver === 'pgsql') {
            DB::statement("ALTER TABLE certificates ALTER COLUMN hours DROP NOT NULL");
            DB::statement("ALTER TABLE certificates ALTER COLUMN hours DROP DEFAULT");
            return;
        }

        // SQLite / others: no-op
    }
};
