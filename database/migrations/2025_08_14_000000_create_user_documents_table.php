<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('user_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type', 40)->index(); // id_card, passport, school_certificate, college_certificate, other
            $table->string('name')->nullable();
            $table->string('file_path');          // e.g. storage/uploads/documents/{user_id}/file.pdf
            $table->string('mime', 100)->nullable();
            $table->unsignedBigInteger('size')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('user_documents');
    }
};
