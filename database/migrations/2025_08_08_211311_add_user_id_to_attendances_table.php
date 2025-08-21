<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            if (! Schema::hasColumn('attendances', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->index();
            }
            // If this migration ever tried to add opportunity_id too, guard it:
            if (! Schema::hasColumn('attendances', 'opportunity_id')) {
                $table->unsignedBigInteger('opportunity_id')->nullable()->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            if (Schema::hasColumn('attendances', 'user_id')) {
                $table->dropColumn('user_id');
            }
            if (Schema::hasColumn('attendances', 'opportunity_id')) {
                $table->dropColumn('opportunity_id');
            }
        });
    }
};
