<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('volunteers')) {
            Schema::table('volunteers', function (Blueprint $table) {
                if (!Schema::hasColumn('volunteers', 'address')) {
                    $table->string('address')->nullable();
                }
                if (!Schema::hasColumn('volunteers', 'date_of_birth')) {
                    $table->date('date_of_birth')->nullable();
                }
                if (!Schema::hasColumn('volunteers', 'skills')) {
                    $table->text('skills')->nullable();
                }
                if (!Schema::hasColumn('volunteers', 'interests')) {
                    $table->text('interests')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('volunteers')) {
            Schema::table('volunteers', function (Blueprint $table) {
                foreach (['address','date_of_birth','skills','interests'] as $col) {
                    if (Schema::hasColumn('volunteers', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
