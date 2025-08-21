<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('opportunities')) return;
        Schema::table('opportunities', function (Blueprint $t) {
            if (!Schema::hasColumn('opportunities','checkin_token'))  $t->string('checkin_token', 64)->nullable()->unique()->after('id');
            if (!Schema::hasColumn('opportunities','checkout_token')) $t->string('checkout_token',64)->nullable()->unique()->after('checkin_token');
        });
    }
    public function down(): void { /* keep safe */ }
};
