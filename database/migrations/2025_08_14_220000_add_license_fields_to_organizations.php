<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            if (!Schema::hasColumn('organizations', 'license_file_path')) {
                $table->string('license_file_path')->nullable()->after('logo_path');
            }
            if (!Schema::hasColumn('organizations', 'license_number')) {
                $table->string('license_number', 120)->nullable()->after('license_file_path');
            }
            if (!Schema::hasColumn('organizations', 'review_notes')) {
                $table->text('review_notes')->nullable()->after('license_status');
            }
            // Normalize review flow states
            if (Schema::hasColumn('organizations', 'license_status')) {
                // values used: none|requested|in_review|approved|rejected
                // we’ll set in_review on registration
            }
        });
    }

    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            foreach (['license_file_path','license_number','review_notes'] as $col) {
                if (Schema::hasColumn('organizations', $col)) $table->dropColumn($col);
            }
        });
    }
};
