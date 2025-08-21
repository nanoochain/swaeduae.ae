<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('organizations')) return;

        if (!Schema::hasColumn('organizations', 'owner_user_id')) {
            Schema::table('organizations', function (Blueprint $table) {
                $table->unsignedBigInteger('owner_user_id')->nullable()->after('id');
                $table->index('owner_user_id', 'organizations_owner_user_id_idx');
            });

            // Try to backfill from existing columns if they exist
            if (Schema::hasColumn('organizations', 'owner_id')) {
                DB::statement("
                    UPDATE organizations
                    SET owner_user_id = owner_id
                    WHERE owner_user_id IS NULL AND owner_id IS NOT NULL
                ");
            } elseif (Schema::hasColumn('organizations', 'user_id')) {
                DB::statement("
                    UPDATE organizations
                    SET owner_user_id = user_id
                    WHERE owner_user_id IS NULL AND user_id IS NOT NULL
                ");
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('organizations') && Schema::hasColumn('organizations', 'owner_user_id')) {
            Schema::table('organizations', function (Blueprint $table) {
                $table->dropIndex('organizations_owner_user_id_idx');
                $table->dropColumn('owner_user_id');
            });
        }
    }
};
