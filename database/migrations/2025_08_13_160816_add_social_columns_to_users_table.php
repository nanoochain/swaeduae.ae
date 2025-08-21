<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $t) {
            if (!Schema::hasColumn('users','provider'))    $t->string('provider')->nullable()->index();
            if (!Schema::hasColumn('users','provider_id')) $t->string('provider_id')->nullable()->index();
            if (!Schema::hasColumn('users','avatar'))      $t->string('avatar')->nullable();
        });
    }
    public function down(): void {
        Schema::table('users', function (Blueprint $t) {
            if (Schema::hasColumn('users','avatar'))      $t->dropColumn('avatar');
            if (Schema::hasColumn('users','provider_id')) $t->dropColumn('provider_id');
            if (Schema::hasColumn('users','provider'))    $t->dropColumn('provider');
        });
    }
};
