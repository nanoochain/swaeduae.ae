<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('attendances')) return;

        Schema::table('attendances', function (Blueprint $t) {
            if (! Schema::hasColumn('attendances', 'checkin_at')) {
                $t->timestamp('checkin_at')->nullable()->after('opportunity_id');
            }
            if (! Schema::hasColumn('attendances', 'checkout_at')) {
                $t->timestamp('checkout_at')->nullable()->after('checkin_at');
            }
            if (! Schema::hasColumn('attendances', 'token')) {
                $t->uuid('token')->nullable()->after('checkout_at');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('attendances')) return;

        Schema::table('attendances', function (Blueprint $t) {
            if (Schema::hasColumn('attendances', 'checkin_at')) {
                $t->dropColumn('checkin_at');
            }
            if (Schema::hasColumn('attendances', 'checkout_at')) {
                $t->dropColumn('checkout_at');
            }
            if (Schema::hasColumn('attendances', 'token')) {
                $t->dropColumn('token');
            }
        });
    }
};
