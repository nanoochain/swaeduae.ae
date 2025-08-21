<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('qr_scans')) {
            Schema::create('qr_scans', function (Blueprint $t) {
                $t->id();
                $t->foreignId('user_id')->nullable()->index();
                $t->foreignId('opportunity_id')->nullable()->index();
                $t->string('action')->default('checkin'); // checkin|checkout
                $t->string('code')->nullable()->index();
                $t->decimal('lat', 10, 7)->nullable();
                $t->decimal('lng', 10, 7)->nullable();
                $t->ipAddress('ip')->nullable();
                $t->timestamp('scanned_at')->useCurrent();
                $t->timestamps();
            });
        }
    }
    public function down(): void {
        Schema::dropIfExists('qr_scans');
    }
};
