<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('attendances', function (Blueprint $table) {
            if (!Schema::hasColumn('attendances', 'minutes')) {
                $table->integer('minutes')->nullable()->after('check_out_at');
            }
            if (!Schema::hasColumn('attendances', 'no_show')) {
                $table->boolean('no_show')->default(false)->after('minutes');
            }
            if (!Schema::hasColumn('attendances', 'notes')) {
                $table->text('notes')->nullable()->after('no_show');
            }
        });
    }
    public function down(): void {
        Schema::table('attendances', function (Blueprint $table) {
            if (Schema::hasColumn('attendances', 'minutes')) $table->dropColumn('minutes');
            if (Schema::hasColumn('attendances', 'no_show')) $table->dropColumn('no_show');
            if (Schema::hasColumn('attendances', 'notes')) $table->dropColumn('notes');
        });
    }
};
