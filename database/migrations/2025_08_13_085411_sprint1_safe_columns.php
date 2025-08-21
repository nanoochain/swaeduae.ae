<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Add capacity + waitlist_enabled (without 'after' so it works regardless of column order)
        if (Schema::hasTable('opportunities')) {
            Schema::table('opportunities', function (Blueprint $table) {
                if (!Schema::hasColumn('opportunities', 'capacity')) {
                    $table->integer('capacity')->nullable();
                }
                if (!Schema::hasColumn('opportunities', 'waitlist_enabled')) {
                    $table->boolean('waitlist_enabled')->default(true);
                }
            });
        }

        // volunteer_hours extras (safe/idempotent)
        if (Schema::hasTable('volunteer_hours')) {
            Schema::table('volunteer_hours', function (Blueprint $table) {
                if (!Schema::hasColumn('volunteer_hours', 'notes')) {
                    $table->text('notes')->nullable();
                }
                if (!Schema::hasColumn('volunteer_hours', 'source')) {
                    $table->string('source', 64)->nullable();
                }
                if (!Schema::hasColumn('volunteer_hours', 'opportunity_id')) {
                    $table->unsignedBigInteger('opportunity_id')->nullable()->index();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('opportunities')) {
            Schema::table('opportunities', function (Blueprint $table) {
                if (Schema::hasColumn('opportunities', 'waitlist_enabled')) $table->dropColumn('waitlist_enabled');
                if (Schema::hasColumn('opportunities', 'capacity')) $table->dropColumn('capacity');
            });
        }
        if (Schema::hasTable('volunteer_hours')) {
            Schema::table('volunteer_hours', function (Blueprint $table) {
                if (Schema::hasColumn('volunteer_hours', 'source')) $table->dropColumn('source');
                if (Schema::hasColumn('volunteer_hours', 'notes')) $table->dropColumn('notes');
                if (Schema::hasColumn('volunteer_hours', 'opportunity_id')) $table->dropColumn('opportunity_id');
            });
        }
    }
};
