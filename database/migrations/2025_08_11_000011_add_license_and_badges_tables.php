<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Add license_number to volunteers if missing (no 'after' to avoid column dependency)
        if (Schema::hasTable('volunteers') && !Schema::hasColumn('volunteers', 'license_number')) {
            Schema::table('volunteers', function (Blueprint $table) {
                $table->string('license_number')->nullable();
            });
        }

        // Create badges table if missing
        if (!Schema::hasTable('badges')) {
            Schema::create('badges', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->integer('threshold')->default(0);
                $table->timestamps();
            });
        }

        // Create badge_user pivot if missing
        if (!Schema::hasTable('badge_user')) {
            Schema::create('badge_user', function (Blueprint $table) {
                $table->id();
                $table->foreignId('badge_id')->constrained()->cascadeOnDelete();
                $table->foreignId('volunteer_id')->constrained('users')->cascadeOnDelete();
                $table->timestamps();
            });
        }

        // Create volunteer_hours if missing
        if (!Schema::hasTable('volunteer_hours')) {
            Schema::create('volunteer_hours', function (Blueprint $table) {
                $table->id();
                $table->foreignId('volunteer_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('opportunity_id')->constrained()->cascadeOnDelete();
                $table->integer('hours')->default(0);
                $table->timestamps();
            });
        }

        // Add license_number to teams if missing
        if (Schema::hasTable('teams') && !Schema::hasColumn('teams', 'license_number')) {
            Schema::table('teams', function (Blueprint $table) {
                $table->string('license_number')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('volunteers') && Schema::hasColumn('volunteers', 'license_number')) {
            Schema::table('volunteers', function (Blueprint $table) {
                $table->dropColumn('license_number');
            });
        }

        if (Schema::hasTable('badge_user')) {
            Schema::dropIfExists('badge_user');
        }

        if (Schema::hasTable('badges')) {
            Schema::dropIfExists('badges');
        }

        if (Schema::hasTable('volunteer_hours')) {
            Schema::dropIfExists('volunteer_hours');
        }

        if (Schema::hasTable('teams') && Schema::hasColumn('teams', 'license_number')) {
            Schema::table('teams', function (Blueprint $table) {
                $table->dropColumn('license_number');
            });
        }
    }
};
