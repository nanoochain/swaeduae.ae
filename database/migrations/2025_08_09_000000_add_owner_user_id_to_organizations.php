<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('organizations') && !Schema::hasColumn('organizations', 'owner_user_id')) {
            Schema::table('organizations', function (Blueprint $t) {
                $t->unsignedBigInteger('owner_user_id')->nullable()->index()->after('id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('organizations') && Schema::hasColumn('organizations', 'owner_user_id')) {
            Schema::table('organizations', function (Blueprint $t) {
                // dropIndex accepts the index name or columns array; columns array works here
                $t->dropIndex(['owner_user_id']);
                $t->dropColumn('owner_user_id');
            });
        }
    }
};
