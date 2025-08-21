<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('certificates', function (Blueprint $table) {
            if (!Schema::hasColumn('certificates', 'hours')) {
                $table->decimal('hours', 6, 2)->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('certificates', 'file_path')) {
                $table->string('file_path')->nullable()->after('hours');
            }
            if (!Schema::hasColumn('certificates', 'code')) {
                $table->string('code', 32)->unique()->nullable()->after('file_path');
            }
            if (!Schema::hasColumn('certificates', 'issued_at')) {
                $table->timestamp('issued_at')->nullable()->after('code');
            }
        });
    }
    public function down(): void {
        Schema::table('certificates', function (Blueprint $table) {
            if (Schema::hasColumn('certificates', 'hours')) $table->dropColumn('hours');
            if (Schema::hasColumn('certificates', 'file_path')) $table->dropColumn('file_path');
            if (Schema::hasColumn('certificates', 'code')) $table->dropColumn('code');
            if (Schema::hasColumn('certificates', 'issued_at')) $table->dropColumn('issued_at');
        });
    }
};
