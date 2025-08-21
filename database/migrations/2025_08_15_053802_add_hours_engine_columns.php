<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('opportunities')) {
            Schema::table('opportunities', function (Blueprint $table) {
                if (!Schema::hasColumn('opportunities','geofence_radius')) {
                    $table->unsignedInteger('geofence_radius')->default(150);
                }
                if (!Schema::hasColumn('opportunities','clip_to_shift')) {
                    $table->boolean('clip_to_shift')->default(true);
                }
            });
        }

        if (Schema::hasTable('attendances')) {
            Schema::table('attendances', function (Blueprint $table) {
                if (!Schema::hasColumn('attendances','method')) {
                    $table->string('method', 20)->default('qr');
                }
                if (!Schema::hasColumn('attendances','lat')) {
                    $table->decimal('lat', 10, 7)->nullable();
                }
                if (!Schema::hasColumn('attendances','lng')) {
                    $table->decimal('lng', 10, 7)->nullable();
                }
                if (!Schema::hasColumn('attendances','device_hash')) {
                    $table->string('device_hash', 64)->nullable();
                }
            });
        }

        if (Schema::hasTable('volunteer_hours')) {
            Schema::table('volunteer_hours', function (Blueprint $table) {
                // Do NOT add minutes if your schema already has it (safe check)
                if (!Schema::hasColumn('volunteer_hours','minutes')) {
                    $table->unsignedInteger('minutes')->nullable();
                }
                if (!Schema::hasColumn('volunteer_hours','confidence_score')) {
                    $table->unsignedTinyInteger('confidence_score')->nullable();
                }
                if (!Schema::hasColumn('volunteer_hours','anomaly_flags')) {
                    $table->json('anomaly_flags')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('opportunities')) {
            Schema::table('opportunities', function (Blueprint $table) {
                if (Schema::hasColumn('opportunities','clip_to_shift')) $table->dropColumn('clip_to_shift');
                if (Schema::hasColumn('opportunities','geofence_radius')) $table->dropColumn('geofence_radius');
            });
        }
        if (Schema::hasTable('attendances')) {
            Schema::table('attendances', function (Blueprint $table) {
                if (Schema::hasColumn('attendances','device_hash')) $table->dropColumn('device_hash');
                if (Schema::hasColumn('attendances','lng'))         $table->dropColumn('lng');
                if (Schema::hasColumn('attendances','lat'))         $table->dropColumn('lat');
                if (Schema::hasColumn('attendances','method'))      $table->dropColumn('method');
            });
        }
        if (Schema::hasTable('volunteer_hours')) {
            // Do NOT drop 'minutes' because some installs already had it before this migration.
            Schema::table('volunteer_hours', function (Blueprint $table) {
                if (Schema::hasColumn('volunteer_hours','anomaly_flags'))   $table->dropColumn('anomaly_flags');
                if (Schema::hasColumn('volunteer_hours','confidence_score')) $table->dropColumn('confidence_score');
            });
        }
    }
};
