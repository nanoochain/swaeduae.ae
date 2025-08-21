<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('learning_requests')) {
            Schema::create('learning_requests', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->index();
                $table->string('title', 190);
                $table->text('details')->nullable();
                $table->enum('status', ['pending','approved','rejected'])->default('pending');
                $table->timestamps();
            });
        }
    }
    public function down(): void { /* keep */ }
};
