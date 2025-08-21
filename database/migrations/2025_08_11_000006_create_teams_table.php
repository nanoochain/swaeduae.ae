<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Create teams table only if it doesn't already exist
        if (!Schema::hasTable('teams')) {
            Schema::create('teams', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->foreignId('leader_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('license_number')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        // Create pivot table only if it doesn't already exist
        if (!Schema::hasTable('team_volunteer')) {
            Schema::create('team_volunteer', function (Blueprint $table) {
                $table->id();
                $table->foreignId('team_id')->constrained()->cascadeOnDelete();
                $table->foreignId('volunteer_id')->constrained('users')->cascadeOnDelete();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // Drop tables only if they exist
        if (Schema::hasTable('team_volunteer')) {
            Schema::dropIfExists('team_volunteer');
        }
        if (Schema::hasTable('teams')) {
            Schema::dropIfExists('teams');
        }
    }
};
