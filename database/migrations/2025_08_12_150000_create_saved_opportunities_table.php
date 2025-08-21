<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('saved_opportunities')) {
            Schema::create('saved_opportunities', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('opportunity_id');
                $table->timestamps();
                $table->unique(['user_id','opportunity_id']);
            });
        }
    }
    public function down(): void {
        Schema::dropIfExists('saved_opportunities');
    }
};
