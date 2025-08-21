<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('opportunities', function (Blueprint $table) {
            if (!Schema::hasColumn('opportunities','summary')) $table->text('summary')->nullable();
            if (!Schema::hasColumn('opportunities','location')) $table->string('location')->nullable();
            if (!Schema::hasColumn('opportunities','region')) $table->string('region')->nullable();
            if (!Schema::hasColumn('opportunities','date')) $table->date('date')->nullable();
            if (!Schema::hasColumn('opportunities','start_time')) $table->time('start_time')->nullable();
            if (!Schema::hasColumn('opportunities','end_time')) $table->time('end_time')->nullable();
            if (!Schema::hasColumn('opportunities','status')) $table->string('status')->default('open');
        });
    }

    public function down(): void {
        Schema::table('opportunities', function (Blueprint $table) {
            if (Schema::hasColumn('opportunities','summary')) $table->dropColumn('summary');
            if (Schema::hasColumn('opportunities','location')) $table->dropColumn('location');
            if (Schema::hasColumn('opportunities','region')) $table->dropColumn('region');
            if (Schema::hasColumn('opportunities','date')) $table->dropColumn('date');
            if (Schema::hasColumn('opportunities','start_time')) $table->dropColumn('start_time');
            if (Schema::hasColumn('opportunities','end_time')) $table->dropColumn('end_time');
            if (Schema::hasColumn('opportunities','status')) $table->dropColumn('status');
        });
    }
};
