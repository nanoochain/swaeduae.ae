<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            if (!Schema::hasColumn('organizations', 'license_file_path')) {
                $table->string('license_file_path')->nullable()->after('license_number');
            }
        });

        // Change nullability (requires doctrine/dbal)
        Schema::table('organizations', function (Blueprint $table) {
            if (Schema::hasColumn('organizations', 'email')) {
                $table->string('email')->nullable()->change();
            }
            if (Schema::hasColumn('organizations', 'password')) {
                $table->string('password')->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            if (Schema::hasColumn('organizations', 'license_file_path')) {
                $table->dropColumn('license_file_path');
            }
            if (Schema::hasColumn('organizations', 'email')) {
                $table->string('email')->nullable(false)->change();
            }
            if (Schema::hasColumn('organizations', 'password')) {
                $table->string('password')->nullable(false)->change();
            }
        });
    }
};
