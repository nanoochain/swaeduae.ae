<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('audit_logs')) {
            Schema::create('audit_logs', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->string('action', 100);
                $table->string('target_type', 100)->nullable();
                $table->unsignedBigInteger('target_id')->nullable();
                $table->json('meta')->nullable();
                $table->timestamps();

                $table->index(['target_type', 'target_id']);
            });
        } else {
            // Ensure important columns exist if the table was created earlier
            Schema::table('audit_logs', function (Blueprint $table) {
                if (!Schema::hasColumn('audit_logs', 'user_id'))     { $table->unsignedBigInteger('user_id')->nullable()->index()->after('id'); }
                if (!Schema::hasColumn('audit_logs', 'action'))      { $table->string('action', 100)->after('user_id'); }
                if (!Schema::hasColumn('audit_logs', 'target_type')) { $table->string('target_type', 100)->nullable()->after('action'); }
                if (!Schema::hasColumn('audit_logs', 'target_id'))   { $table->unsignedBigInteger('target_id')->nullable()->after('target_type'); }
                if (!Schema::hasColumn('audit_logs', 'meta'))        { $table->json('meta')->nullable()->after('target_id'); }
                if (!Schema::hasColumn('audit_logs', 'created_at'))  { $table->timestamp('created_at')->nullable()->after('meta'); }
                if (!Schema::hasColumn('audit_logs', 'updated_at'))  { $table->timestamp('updated_at')->nullable()->after('created_at'); }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
