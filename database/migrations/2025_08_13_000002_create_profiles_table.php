<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('profiles')) {
            Schema::create('profiles', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->index();
                $table->string('first_name', 120)->nullable();
                $table->string('last_name', 120)->nullable();
                $table->date('date_of_birth')->nullable();
                $table->string('gender', 20)->nullable();
                $table->string('nationality', 80)->nullable();
                $table->string('emirate', 80)->nullable();
                $table->string('photo')->nullable();
                $table->json('skills')->nullable();
                $table->json('interests')->nullable();
                $table->json('availability')->nullable();
                $table->text('address')->nullable();
                $table->enum('kyc_status', ['pending','verified','rejected'])->default('pending');
                $table->timestamps();
            });
        }
    }
    public function down(): void {
        // no drop on shared hosting
    }
};
