<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('organization_registrations')) {
            Schema::create('organization_registrations', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->index();
                $table->string('organization_name');
                $table->string('trade_license_number');
                $table->string('phone', 50);
                $table->string('website')->nullable();
                $table->string('emirate')->nullable();
                $table->string('city')->nullable();
                $table->string('address')->nullable();
                $table->string('contact_person_name');
                $table->string('contact_person_email');
                $table->string('contact_person_phone', 50);
                $table->string('sector')->nullable();
                $table->text('description')->nullable();
                $table->string('status')->default('pending')->index();
                $table->timestamps();
            });
        }
    }
    public function down(): void {
        Schema::dropIfExists('organization_registrations');
    }
};
