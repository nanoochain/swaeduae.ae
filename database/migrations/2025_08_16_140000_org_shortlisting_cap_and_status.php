<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Add slot_cap to opportunities (nullable => unlimited)
        if (Schema::hasTable('opportunities') && !Schema::hasColumn('opportunities', 'slot_cap')) {
            Schema::table('opportunities', function (Blueprint $table) {
                $table->unsignedInteger('slot_cap')->nullable()->after('title')->comment('Max approved volunteers; null=unlimited');
            });
        }

        // Try to augment an existing applications-like table
        $candidateTables = ['applications', 'opportunity_applications', 'applications_opportunities'];
        $augmented = false;
        foreach ($candidateTables as $t) {
            if (Schema::hasTable($t)) {
                Schema::table($t, function (Blueprint $table) use ($t) {
                    if (!Schema::hasColumn($t, 'status')) {
                        $table->string('status', 20)->default('pending')->index();
                    }
                    if (!Schema::hasColumn($t, 'status_changed_at')) {
                        $table->timestamp('status_changed_at')->nullable()->index();
                    }
                    if (!Schema::hasColumn($t, 'notes')) {
                        $table->text('notes')->nullable();
                    }
                    if (!Schema::hasColumn($t, 'source')) {
                        $table->string('source', 50)->nullable();
                    }
                });
                $augmented = true;
                break;
            }
        }

        // If none exists, create a minimal applications table (non-breaking)
        if (!$augmented && !Schema::hasTable('applications')) {
            Schema::create('applications', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('user_id')->index();
                $table->unsignedBigInteger('opportunity_id')->index();
                $table->string('status', 20)->default('pending')->index();
                $table->timestamp('status_changed_at')->nullable()->index();
                $table->text('notes')->nullable();
                $table->string('source', 50)->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // Non-destructive: do not drop columns/tables.
    }
};
