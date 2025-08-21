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
                try { $t->dropUnique('attendances_user_id_opportunity_id_unique'); } catch (\Throwable $e) {}
            });
            return;
        }
    }

    public function down(): void
    {
        // no-op (we don't want this unique back)
    }
};
