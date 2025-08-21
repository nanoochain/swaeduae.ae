<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('opportunity_user')) {
            Schema::create('opportunity_user', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('opportunity_id')->constrained()->onDelete('cascade');
                $table->string('status')->default('pending');
                $table->timestamps();
            });
        }
    }
    public function down(): void {
        if (Schema::hasTable('opportunity_user')) {
            Schema::dropIfExists('opportunity_user');
        }
    }
};
