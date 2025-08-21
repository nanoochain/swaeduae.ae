<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('opportunities')) {
            Schema::create('opportunities', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description')->nullable();
                $table->date('date')->nullable();
                $table->time('time')->nullable();
                $table->foreignId('location_id')->nullable()->constrained()->nullOnDelete();
                $table->foreignId('organization_id')->nullable()->constrained()->nullOnDelete();
                $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
                $table->integer('volunteers_needed')->default(0);
                $table->boolean('featured')->default(false);
                $table->string('image')->nullable();
                $table->string('region')->nullable();
                $table->string('badge')->nullable();
                $table->timestamps();
            });
        }
    }
    public function down(): void {
        if (Schema::hasTable('opportunities')) {
            Schema::dropIfExists('opportunities');
        }
    }
};
