<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    protected string $indexName = 'attendances_user_id_opportunity_id_unique';

    public function up(): void
    {
        if (! Schema::hasTable('attendances')) return;

        // Ensure the two columns exist (safe-guard; no-ops if already there)
        Schema::table('attendances', function (Blueprint $t) {
            if (! Schema::hasColumn('attendances', 'user_id')) {
                $t->unsignedBigInteger('user_id')->nullable();
            }
            if (! Schema::hasColumn('attendances', 'opportunity_id')) {
                $t->unsignedBigInteger('opportunity_id')->nullable();
            }
        });

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'sqlite') {
            // SQLite: PRAGMA-compatible "IF NOT EXISTS"
            DB::statement("CREATE UNIQUE INDEX IF NOT EXISTS {$this->indexName} ON attendances (user_id, opportunity_id)");
            return;
        }

        if ($driver === 'pgsql') {
            // Postgres supports IF NOT EXISTS
            DB::statement("CREATE UNIQUE INDEX IF NOT EXISTS {$this->indexName} ON attendances (user_id, opportunity_id)");
            return;
        }

        if ($driver === 'mysql') {
            // MySQL: check via SHOW INDEX, then add once
            $exists = collect(DB::select("SHOW INDEX FROM attendances WHERE Key_name = ?", [$this->indexName]))->isNotEmpty();
            if (! $exists) {
                Schema::table('attendances', function (Blueprint $t) {
                    $t->unique(['user_id','opportunity_id'], $this->indexName);
                });
            }
            return;
        }

        // Fallback: attempt once (may fail if it already exists)
        Schema::table('attendances', function (Blueprint $t) {
            $t->unique(['user_id','opportunity_id'], $this->indexName);
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('attendances')) return;

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'sqlite') {
            DB::statement("DROP INDEX IF EXISTS {$this->indexName}");
            return;
        }

        if ($driver === 'pgsql') {
            DB::statement("DROP INDEX IF EXISTS {$this->indexName}");
            return;
        }

        if ($driver === 'mysql') {
            Schema::table('attendances', function (Blueprint $t) {
                $t->dropUnique($this->indexName);
            });
            return;
        }
    }
};
