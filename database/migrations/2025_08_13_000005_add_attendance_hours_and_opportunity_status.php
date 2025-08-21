<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // attendances
        if (!Schema::hasTable('attendances')) {
            Schema::create('attendances', function (Blueprint $t) {
                $t->id();
                $t->unsignedBigInteger('user_id')->index();
                $t->unsignedBigInteger('opportunity_id')->index();
                $t->timestamp('checkin_at')->nullable()->index();
                $t->timestamp('checkout_at')->nullable()->index();
                $t->enum('status',['checked_in','checked_out','flagged'])->default('checked_in')->index();
                $t->string('source', 20)->nullable(); // qr/manual
                $t->text('notes')->nullable();
                $t->timestamps();
                $t->unique(['user_id','opportunity_id']); // 1 row per user/opportunity
            });
        }

        // volunteer_hours (finalized/locked)
        if (!Schema::hasTable('volunteer_hours')) {
            Schema::create('volunteer_hours', function (Blueprint $t) {
                $t->id();
                $t->unsignedBigInteger('user_id')->index();
                $t->unsignedBigInteger('opportunity_id')->index();
                $t->integer('minutes')->default(0);
                $t->enum('source', ['attendance','manual'])->default('attendance');
                $t->boolean('locked')->default(true)->index();
                $t->timestamps();
                $t->unique(['user_id','opportunity_id','source']);
            });
        }

        // opportunities: status, completed_at, QR tokens
        Schema::table('opportunities', function (Blueprint $t) {
            if (!Schema::hasColumn('opportunities','status')) {
                $t->string('status', 20)->default('open')->index();
            }
            if (!Schema::hasColumn('opportunities','completed_at')) {
                $t->timestamp('completed_at')->nullable()->index();
            }
            if (!Schema::hasColumn('opportunities','checkin_token')) {
                $t->string('checkin_token', 64)->nullable()->unique();
            }
            if (!Schema::hasColumn('opportunities','checkout_token')) {
                $t->string('checkout_token', 64)->nullable()->unique();
            }
        });
    }
    public function down(): void { /* keep safe on shared hosting */ }
};
