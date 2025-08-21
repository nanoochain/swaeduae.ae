<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('volunteer_hours')) {
            Schema::create('volunteer_hours', function (Blueprint $t) {
                $t->id();
                $t->foreignId('user_id')->constrained()->cascadeOnDelete();
                $t->foreignId('opportunity_id')->nullable()->index();
                $t->decimal('hours', 6, 2)->default(0);
                $t->text('note')->nullable();
                $t->timestamps();
            });
        }
    }
    public function down(): void {
        Schema::dropIfExists('volunteer_hours');
    }
};
