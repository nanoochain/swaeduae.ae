<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'bio')) $table->text('bio')->nullable()->after('email');
            if (!Schema::hasColumn('users', 'skills')) $table->string('skills')->nullable()->after('bio');
            if (!Schema::hasColumn('users', 'location')) $table->string('location')->nullable()->after('skills');
            if (!Schema::hasColumn('users', 'languages')) $table->string('languages')->nullable()->after('location');
            if (!Schema::hasColumn('users', 'profile_photo')) $table->string('profile_photo')->nullable()->after('languages');
        });
    }
    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            foreach (['bio','skills','location','languages','profile_photo'] as $col) {
                if (Schema::hasColumn('users', $col)) $table->dropColumn($col);
            }
        });
    }
};
