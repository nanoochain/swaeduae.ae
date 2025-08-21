<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            if (!Schema::hasColumn('attendances', 'checkin_at')) {
                $table->timestamp('checkin_at')->nullable()->index();
            }
            if (!Schema::hasColumn('attendances', 'checkout_at')) {
                $table->timestamp('checkout_at')->nullable()->index();
            }
            if (!Schema::hasColumn('attendances', 'checkin_lat')) {
                $table->decimal('checkin_lat', 10, 7)->nullable();
            }
            if (!Schema::hasColumn('attendances', 'checkin_lng')) {
                $table->decimal('checkin_lng', 10, 7)->nullable();
            }
            if (!Schema::hasColumn('attendances', 'checkout_lat')) {
                $table->decimal('checkout_lat', 10, 7)->nullable();
            }
            if (!Schema::hasColumn('attendances', 'checkout_lng')) {
                $table->decimal('checkout_lng', 10, 7)->nullable();
            }
            if (!Schema::hasColumn('attendances', 'minutes')) {
                $table->unsignedInteger('minutes')->default(0)->index();
            }
            if (!Schema::hasColumn('attendances', 'status')) {
                $table->string('status', 20)->default('present')->index(); // present|no_show|pending
            }
            if (!Schema::hasColumn('attendances', 'is_locked')) {
                $table->boolean('is_locked')->default(false)->index();
            }
            if (!Schema::hasColumn('attendances', 'finalized_at')) {
                $table->timestamp('finalized_at')->nullable()->index();
            }
        });
    }

    public function down(): void
    {
        // Non-destructive: keep columns; do not drop to avoid data loss.
    }
};
