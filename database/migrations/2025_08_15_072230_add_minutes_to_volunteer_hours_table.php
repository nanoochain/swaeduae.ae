<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('volunteer_hours', function (Blueprint $table) {
            if (!Schema::hasColumn('volunteer_hours', 'minutes')) {
                $table->integer('minutes')->default(0)->after('hours');
            }
        });
    }

    public function down(): void {
        Schema::table('volunteer_hours', function (Blueprint $table) {
            if (Schema::hasColumn('volunteer_hours', 'minutes')) {
                $table->dropColumn('minutes');
            }
        });
    }
};
