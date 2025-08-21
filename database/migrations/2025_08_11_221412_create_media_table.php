<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('media')) {
            Schema::create('media', function (Blueprint $table) {
                $table->id();
                $table->string('disk')->default('public');
                $table->string('path');
                $table->string('original_name');
                $table->string('mime', 128)->nullable();
                $table->unsignedBigInteger('size')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // Intentionally left safe; do not drop an existing table automatically.
        // Schema::dropIfExists('media');
    }
};
