<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    protected string $codeIndex = 'certificates_code_index';

    public function up(): void
    {
        if (! Schema::hasTable('certificates')) return;

        // Make sure the column exists so index creation won't fail.
        Schema::table('certificates', function (Blueprint $t) {
            if (! Schema::hasColumn('certificates', 'code')) {
                $t->string('code')->nullable();
            }
        });

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'sqlite') {
            DB::statement("CREATE INDEX IF NOT EXISTS {$this->codeIndex} ON certificates (code)");
            return;
        }

        if ($driver === 'pgsql') {
            DB::statement("CREATE INDEX IF NOT EXISTS {$this->codeIndex} ON certificates (code)");
            return;
        }

        if ($driver === 'mysql') {
            $exists = collect(DB::select('SHOW INDEX FROM certificates WHERE Key_name = ?', [$this->codeIndex]))->isNotEmpty();
            if (! $exists) {
                Schema::table('certificates', function (Blueprint $t) {
                    $t->index('code', $this->codeIndex);
                });
            }
            return;
        }

        // Fallback for other drivers: try once
        Schema::table('certificates', function (Blueprint $t) {
            $t->index('code', $this->codeIndex);
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('certificates')) return;

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'sqlite') {
            DB::statement("DROP INDEX IF EXISTS {$this->codeIndex}");
            return;
        }

        if ($driver === 'pgsql') {
            DB::statement("DROP INDEX IF EXISTS {$this->codeIndex}");
            return;
        }

        if ($driver === 'mysql') {
            Schema::table('certificates', function (Blueprint $t) {
                $t->dropIndex($this->codeIndex);
            });
            return;
        }
    }
};
