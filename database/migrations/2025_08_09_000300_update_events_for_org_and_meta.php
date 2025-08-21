<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (Schema::hasTable('events')) {
            Schema::table('events', function (Blueprint $table) {
                if (!Schema::hasColumn('events','organization_id')) $table->unsignedBigInteger('organization_id')->nullable()->after('id');
                if (!Schema::hasColumn('events','status')) $table->string('status',20)->default('draft')->after('location'); // draft|published|closed
                if (!Schema::hasColumn('events','target')) $table->integer('target')->nullable()->after('status');
                if (!Schema::hasColumn('events','type')) $table->string('type',20)->nullable()->after('target'); // field|virtual
                if (!Schema::hasColumn('events','start_time')) $table->time('start_time')->nullable()->after('date');
                if (!Schema::hasColumn('events','end_time')) $table->time('end_time')->nullable()->after('start_time');
                $table->index(['organization_id','status']);
            });
        }
    }
    public function down(): void {
        // keep columns; non-destructive
    }
};
