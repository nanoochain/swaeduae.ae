<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('events')) {
            Schema::create('events', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description')->nullable();
                $table->date('date')->nullable();
                $table->time('time')->nullable();
                $table->foreignId('location_id')->nullable()->constrained()->nullOnDelete();
                $table->foreignId('organization_id')->nullable()->constrained()->nullOnDelete();
                $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
                $table->integer('volunteers_needed')->default(0);
                $table->string('image')->nullable();
                $table->timestamps();
            });
        }
    }
    public function down(): void {
        if (Schema::hasTable('events')) {
            Schema::dropIfExists('events');
        }
    }
};
