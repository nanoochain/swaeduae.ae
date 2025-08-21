<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('opportunities', function (Blueprint $table) {
            if (! Schema::hasColumn('opportunities', 'category')) {
                $table->string('category')->nullable();
            }
            if (! Schema::hasColumn('opportunities', 'city')) {
                $table->string('city')->nullable();
            }
        });
    }

    public function down(): void
    {
        // Guarded drops (SQLite in-memory will handle this fine in recent Laravel)
        Schema::table('opportunities', function (Blueprint $table) {
            if (Schema::hasColumn('opportunities', 'category')) {
                $table->dropColumn('category');
            }
            if (Schema::hasColumn('opportunities', 'city')) {
                $table->dropColumn('city');
            }
        });
    }
};
