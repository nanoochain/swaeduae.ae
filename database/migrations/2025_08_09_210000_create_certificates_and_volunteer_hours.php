<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertificatesAndVolunteerHours extends Migration
{
    public function up(): void
    {
        // Create certificates table if it doesn't exist
        if (!Schema::hasTable('certificates')) {
            Schema::create('certificates', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('event_id')->nullable()->constrained()->onDelete('cascade');
                $table->string('certificate_number')->unique();
                $table->string('verification_code')->unique();
                $table->string('title');
                $table->text('description')->nullable();
                $table->date('issued_date');
                $table->string('file_path')->nullable();
                $table->timestamps();
            });
        }

        // Create volunteer_hours table if it doesn't exist
        if (!Schema::hasTable('volunteer_hours')) {
            Schema::create('volunteer_hours', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('event_id')->constrained()->onDelete('cascade');
                $table->integer('hours')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('volunteer_hours');
        // We don't drop certificates to avoid accidental data loss
    }
}
