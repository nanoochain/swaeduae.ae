<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('contact_messages')) {
            Schema::create('contact_messages', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name', 150);
                $table->string('email', 190);
                $table->string('subject', 190)->nullable();
                $table->text('message')->nullable();
                $table->string('ip', 64)->nullable();
                $table->string('user_agent', 255)->nullable();
                $table->string('locale', 12)->nullable();
                $table->boolean('is_bot')->default(false);
                $table->timestamps();
            });
        }
    }
    public function down(): void {
        Schema::dropIfExists('contact_messages');
    }
};
