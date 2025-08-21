<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // certificates table (create if absent; else add columns if missing)
        if (!Schema::hasTable('certificates')) {
            Schema::create('certificates', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->index();
                $table->unsignedBigInteger('opportunity_id')->nullable()->index();
                $table->string('title')->nullable();
                $table->string('code')->unique();
                $table->string('file_path')->nullable();
                $table->string('checksum', 191)->nullable()->index();
                $table->timestamp('revoked_at')->nullable()->index();
                $table->timestamps();
            });
        } else {
            Schema::table('certificates', function (Blueprint $table) {
                if (!Schema::hasColumn('certificates', 'user_id')) $table->unsignedBigInteger('user_id')->index()->nullable();
                if (!Schema::hasColumn('certificates', 'opportunity_id')) $table->unsignedBigInteger('opportunity_id')->nullable()->index();
                if (!Schema::hasColumn('certificates', 'title')) $table->string('title')->nullable();
                if (!Schema::hasColumn('certificates', 'code')) $table->string('code')->unique();
                if (!Schema::hasColumn('certificates', 'file_path')) $table->string('file_path')->nullable();
                if (!Schema::hasColumn('certificates', 'checksum')) $table->string('checksum', 191)->nullable()->index();
                if (!Schema::hasColumn('certificates', 'revoked_at')) $table->timestamp('revoked_at')->nullable()->index();
            });
        }

        // delivery logs
        if (!Schema::hasTable('certificate_deliveries')) {
            Schema::create('certificate_deliveries', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('certificate_id')->index();
                $table->string('channel', 32); // email / whatsapp
                $table->string('status', 32)->default('queued'); // queued/sent/failed
                $table->string('meta', 191)->nullable(); // recipient email / wa link
                $table->text('response_excerpt')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('certificate_deliveries')) Schema::drop('certificate_deliveries');
        // don't drop certificates table to preserve history
    }
};
