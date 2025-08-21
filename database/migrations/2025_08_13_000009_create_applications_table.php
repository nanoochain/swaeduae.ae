<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
  public function up(): void {
    if (!Schema::hasTable('applications')) {
      Schema::create('applications', function (Blueprint $t) {
        $t->id();
        $t->unsignedBigInteger('user_id')->index();
        $t->unsignedBigInteger('opportunity_id')->index();
        $t->enum('status',['pending','shortlisted','approved','rejected','cancelled'])->default('pending');
        $t->text('note')->nullable();
        $t->timestamps();
        $t->unique(['user_id','opportunity_id']);
      });
    }
  }
  public function down(): void {}
};
