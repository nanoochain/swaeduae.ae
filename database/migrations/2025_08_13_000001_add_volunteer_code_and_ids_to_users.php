<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users','volunteer_code')) {
                $table->string('volunteer_code', 16)->unique()->nullable()->after('id');
            }
            if (!Schema::hasColumn('users','phone')) {
                $table->string('phone', 32)->nullable()->after('email');
            }
            if (!Schema::hasColumn('users','emirates_id')) {
                $table->string('emirates_id', 32)->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users','uaepass_sub')) {
                $table->string('uaepass_sub', 64)->nullable()->after('emirates_id');
            }
            if (!Schema::hasColumn('users','google_id')) {
                $table->string('google_id', 64)->nullable()->after('uaepass_sub');
            }
            if (!Schema::hasColumn('users','apple_id')) {
                $table->string('apple_id', 64)->nullable()->after('google_id');
            }
        });

        // Backfill volunteer codes using deterministic scheme: SV00 + zero-padded user id
        $users = DB::table('users')->select('id','volunteer_code')->get();
        foreach ($users as $u) {
            if (empty($u->volunteer_code)) {
                $code = 'SV' . str_pad((string)$u->id, 7, '0', STR_PAD_LEFT);
                DB::table('users')->where('id', $u->id)->update(['volunteer_code' => $code]);
            }
        }
    }

    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users','volunteer_code')) $table->dropUnique(['volunteer_code']);
        });
        // keep columns; no destructive rollback on shared hosting
    }
};
