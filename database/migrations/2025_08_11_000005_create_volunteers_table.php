<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Only create the volunteers table if it doesn't already exist
        if (!Schema::hasTable('volunteers')) {
            Schema::create('volunteers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('phone')->nullable();
                $table->string('address')->nullable();
                $table->date('date_of_birth')->nullable();
                $table->text('skills')->nullable();
                $table->text('interests')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // Drop the table only if it exists
        if (Schema::hasTable('volunteers')) {
            Schema::dropIfExists('volunteers');
        }
    }
};
