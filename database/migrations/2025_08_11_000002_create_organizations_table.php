<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('organizations')) {
            Schema::create('organizations', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->string('email');
                $table->string('phone')->nullable();
                $table->string('website')->nullable();
                $table->string('logo_path')->nullable();
                $table->string('address')->nullable();
                $table->string('city')->nullable();
                $table->string('emirate')->nullable();
                $table->boolean('approved')->default(false);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
