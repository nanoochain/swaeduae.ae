<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('kyc_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('pending'); // pending|approved|rejected
            $table->json('data')->nullable();
            $table->string('document_path')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('kyc_requests'); }
};
