<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('attendances', function (Blueprint $table) {
            if (!Schema::hasColumn('attendances', 'checkout_token')) {
                $table->string('checkout_token')->nullable()->unique();
            }
        });
    }
    public function down(): void {
        Schema::table('attendances', function (Blueprint $table) {
            if (Schema::hasColumn('attendances', 'checkout_token')) {
                $table->dropColumn('checkout_token');
            }
        });
    }
};
