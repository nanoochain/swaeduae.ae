<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('geo_logs')) {
            Schema::create('geo_logs', function (Blueprint $t) {
                $t->id();
                $t->foreignId('user_id')->nullable()->index();
                $t->string('context')->nullable(); // e.g., 'checkin', 'checkout'
                $t->decimal('lat', 10, 7)->nullable();
                $t->decimal('lng', 10, 7)->nullable();
                $t->json('meta')->nullable();
                $t->timestamps();
            });
        }
    }
    public function down(): void {
        Schema::dropIfExists('geo_logs');
    }
};
