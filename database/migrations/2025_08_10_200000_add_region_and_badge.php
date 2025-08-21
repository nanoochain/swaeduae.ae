<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('opportunities', function (Blueprint $table) {
            if (!Schema::hasColumn('opportunities', 'region')) {
                $table->string('region')->nullable();
            }
            if (!Schema::hasColumn('opportunities', 'badge')) {
                $table->string('badge')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('opportunities', function (Blueprint $table) {
            if (Schema::hasColumn('opportunities', 'badge')) {
                $table->dropColumn('badge');
            }
            if (Schema::hasColumn('opportunities', 'region')) {
                $table->dropColumn('region');
            }
        });
    }
};
