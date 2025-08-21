<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Attendances table
        if (Schema::hasTable('attendances')) {
            Schema::table('attendances', function (Blueprint $table) {
                if (!Schema::hasColumn('attendances', 'no_show')) {
                    $table->boolean('no_show')->default(false);
                }
                if (!Schema::hasColumn('attendances', 'minutes_reason')) {
                    $table->text('minutes_reason')->nullable();
                }
            });
        }

        // Events table
        if (Schema::hasTable('events')) {
            Schema::table('events', function (Blueprint $table) {
                if (!Schema::hasColumn('events', 'finalized_at')) {
                    $table->timestamp('finalized_at')->nullable();
                }
                if (!Schema::hasColumn('events', 'finalized_by')) {
                    $table->unsignedBigInteger('finalized_by')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('attendances')) {
            Schema::table('attendances', function (Blueprint $table) {
                if (Schema::hasColumn('attendances', 'minutes_reason')) $table->dropColumn('minutes_reason');
                if (Schema::hasColumn('attendances', 'no_show')) $table->dropColumn('no_show');
            });
        }
        if (Schema::hasTable('events')) {
            Schema::table('events', function (Blueprint $table) {
                if (Schema::hasColumn('events', 'finalized_by')) $table->dropColumn('finalized_by');
                if (Schema::hasColumn('events', 'finalized_at')) $table->dropColumn('finalized_at');
            });
        }
    }
};
