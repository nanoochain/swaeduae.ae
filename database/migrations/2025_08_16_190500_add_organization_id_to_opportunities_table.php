<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('opportunities', 'organization_id')) {
            Schema::table('opportunities', function (Blueprint $table) {
                $table->unsignedBigInteger('organization_id')->nullable()->index()->after('id');
            });
            if (Schema::hasColumn('opportunities', 'org_id')) {
                DB::statement('UPDATE opportunities SET organization_id = org_id WHERE organization_id IS NULL');
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('opportunities', 'organization_id')) {
            Schema::table('opportunities', function (Blueprint $table) {
                $table->dropIndex(['organization_id']);
                $table->dropColumn('organization_id');
            });
        }
    }
};
