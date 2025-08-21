<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('settings') && !Schema::hasColumn('settings', 'primary_color')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->string('primary_color', 20)->nullable()->after('id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('settings') && Schema::hasColumn('settings', 'primary_color')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->dropColumn('primary_color');
            });
        }
    }
};
