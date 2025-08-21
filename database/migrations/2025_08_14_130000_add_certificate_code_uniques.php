<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    protected string $indexName = 'certificates_code_unique';

    public function up(): void
    {
        if (! Schema::hasTable('certificates')) return;

        // ensure the 'code' column exists
        Schema::table('certificates', function (Blueprint $t) {
            if (! Schema::hasColumn('certificates', 'code')) {
                $t->string('code')->nullable();
            }
        });

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'sqlite') {
            // SQLite supports IF NOT EXISTS in CREATE INDEX
            DB::statement("CREATE UNIQUE INDEX IF NOT EXISTS {$this->indexName} ON certificates (code)");
            return;
        }

        if ($driver === 'pgsql') {
            // Postgres also supports IF NOT EXISTS
            DB::statement("CREATE UNIQUE INDEX IF NOT EXISTS {$this->indexName} ON certificates (code)");
            return;
        }

        if ($driver === 'mysql') {
            // MySQL: detect with SHOW INDEX, then add if missing
            $exists = collect(DB::select('SHOW INDEX FROM certificates WHERE Key_name = ?', [$this->indexName]))->isNotEmpty();
            if (! $exists) {
                Schema::table('certificates', function (Blueprint $t) {
                    $t->unique('code', $this->indexName);
                });
            }
            return;
        }

        // Fallback (other drivers): try once
        Schema::table('certificates', function (Blueprint $t) {
            $t->unique('code', $this->indexName);
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('certificates')) return;

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
            Schema::table('certificates', function (Blueprint $t) {
                $t->dropUnique($this->indexName);
            });
            return;
        }

        // Fallback: no-op
    }
};
