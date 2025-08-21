<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('audit_logs')) {
            Schema::create('audit_logs', function (Blueprint $t) {
                $t->bigIncrements('id');
                $t->unsignedBigInteger('user_id')->nullable()->index();
                $t->string('role', 64)->nullable();
                $t->string('method', 10)->nullable();
                $t->string('route', 190)->nullable();
                $t->string('route_name', 190)->nullable();
                $t->string('ip', 64)->nullable();
                $t->string('user_agent', 255)->nullable();
                $t->text('payload_excerpt')->nullable();
                $t->timestamps();
            });
        }
    }
    public function down(): void { Schema::dropIfExists('audit_logs'); }
};
