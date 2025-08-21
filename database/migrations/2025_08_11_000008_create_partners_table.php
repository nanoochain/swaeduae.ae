<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('partners')) {
            Schema::create('partners', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('logo')->nullable();
                $table->string('website')->nullable();
                $table->timestamps();
            });
        }
    }
    public function down(): void {
        if (Schema::hasTable('partners')) {
            Schema::dropIfExists('partners');
        }
    }
};
