<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('opportunities','owner_id')) {
            Schema::table('opportunities', function (Blueprint $table) {
                $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete()->after('id');
            });
        }
        if (!Schema::hasColumn('opportunities','status')) {
            Schema::table('opportunities', function (Blueprint $table) {
                // draft|pending|published|rejected
                $table->string('status')->default('draft')->after('is_published')->index();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('opportunities','status')) {
            Schema::table('opportunities', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
        if (Schema::hasColumn('opportunities','owner_id')) {
            Schema::table('opportunities', function (Blueprint $table) {
                $table->dropConstrainedForeignId('owner_id');
            });
        }
    }
};
