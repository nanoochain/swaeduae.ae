<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('certificates')) {
            Schema::create('certificates', function (Blueprint $t) {
                $t->id();
                $t->unsignedBigInteger('user_id')->index();
                $t->unsignedBigInteger('opportunity_id')->nullable()->index();
                $t->decimal('hours', 8, 2)->default(0);
                $t->string('code', 32)->unique();
                $t->uuid('uuid')->unique();
                $t->string('file_path')->nullable();
                $t->timestamps();
            });
            return;
        }
        Schema::table('certificates', function (Blueprint $t) {
            if (!Schema::hasColumn('certificates','user_id')) $t->unsignedBigInteger('user_id')->index()->nullable();
            if (!Schema::hasColumn('certificates','opportunity_id')) $t->unsignedBigInteger('opportunity_id')->index()->nullable();
            if (!Schema::hasColumn('certificates','hours')) $t->decimal('hours', 8, 2)->default(0);
            if (!Schema::hasColumn('certificates','code')) $t->string('code', 32)->nullable()->unique();
            if (!Schema::hasColumn('certificates','uuid')) $t->uuid('uuid')->nullable()->unique();
            if (!Schema::hasColumn('certificates','file_path')) $t->string('file_path')->nullable();
        });
    }
    public function down(): void { /* keep safe */ }
};
