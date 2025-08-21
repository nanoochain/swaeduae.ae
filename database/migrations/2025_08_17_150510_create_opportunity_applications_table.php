<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('opportunity_applications')) {
            Schema::create('opportunity_applications', function (Blueprint $t) {
                $t->id();
                $t->foreignId('user_id')->constrained()->cascadeOnDelete();
                $t->foreignId('opportunity_id')->index();
                $t->string('status')->default('pending'); // pending|accepted|rejected|checked_in|checked_out
                $t->timestamps();
            });
        }
    }
    public function down(): void {
        Schema::dropIfExists('opportunity_applications');
    }
};
