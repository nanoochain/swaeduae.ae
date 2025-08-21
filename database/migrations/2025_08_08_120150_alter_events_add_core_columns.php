<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events','summary')) $table->text('summary')->nullable();
            if (!Schema::hasColumn('events','location')) $table->string('location')->nullable();
            if (!Schema::hasColumn('events','region')) $table->string('region')->nullable();
            if (!Schema::hasColumn('events','category')) $table->string('category')->nullable();
            if (!Schema::hasColumn('events','date')) $table->date('date')->nullable();
            if (!Schema::hasColumn('events','application_deadline')) $table->date('application_deadline')->nullable();
            if (!Schema::hasColumn('events','start_time')) $table->time('start_time')->nullable();
            if (!Schema::hasColumn('events','end_time')) $table->time('end_time')->nullable();
            if (!Schema::hasColumn('events','capacity')) $table->unsignedInteger('capacity')->default(0);
            if (!Schema::hasColumn('events','status')) $table->string('status')->default('open');
            if (!Schema::hasColumn('events','poster_path')) $table->string('poster_path')->nullable();
        });
    }

    public function down(): void {
        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events','poster_path')) $table->dropColumn('poster_path');
            if (Schema::hasColumn('events','status')) $table->dropColumn('status');
            if (Schema::hasColumn('events','capacity')) $table->dropColumn('capacity');
            if (Schema::hasColumn('events','end_time')) $table->dropColumn('end_time');
            if (Schema::hasColumn('events','start_time')) $table->dropColumn('start_time');
            if (Schema::hasColumn('events','application_deadline')) $table->dropColumn('application_deadline');
            if (Schema::hasColumn('events','date')) $table->dropColumn('date');
            if (Schema::hasColumn('events','category')) $table->dropColumn('category');
            if (Schema::hasColumn('events','region')) $table->dropColumn('region');
            if (Schema::hasColumn('events','location')) $table->dropColumn('location');
            if (Schema::hasColumn('events','summary')) $table->dropColumn('summary');
        });
    }
};
