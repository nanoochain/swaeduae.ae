<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // ---- qr_scans ----
        if (Schema::hasTable('qr_scans')) {
            Schema::table('qr_scans', function (Blueprint $t) {
                if (!Schema::hasColumn('qr_scans','user_id'))         $t->unsignedBigInteger('user_id')->nullable()->index()->after('id');
                if (!Schema::hasColumn('qr_scans','opportunity_id'))  $t->unsignedBigInteger('opportunity_id')->nullable()->index()->after('user_id');
                if (!Schema::hasColumn('qr_scans','action'))          $t->string('action', 20)->default('checkin')->after('opportunity_id');
                if (!Schema::hasColumn('qr_scans','code'))            $t->string('code')->nullable()->index()->after('action');
                if (!Schema::hasColumn('qr_scans','lat'))             $t->decimal('lat', 10, 7)->nullable()->after('code');
                if (!Schema::hasColumn('qr_scans','lng'))             $t->decimal('lng', 10, 7)->nullable()->after('lat');
                if (!Schema::hasColumn('qr_scans','ip'))              $t->ipAddress('ip')->nullable()->after('lng');
                if (!Schema::hasColumn('qr_scans','scanned_at'))      $t->timestamp('scanned_at')->useCurrent()->after('ip');

                $hasCreated = Schema::hasColumn('qr_scans','created_at');
                $hasUpdated = Schema::hasColumn('qr_scans','updated_at');
                if (!$hasCreated && !$hasUpdated) {
                    $t->timestamps();
                } elseif (!$hasCreated) {
                    $t->timestamp('created_at')->nullable()->useCurrent();
                } elseif (!$hasUpdated) {
                    $t->timestamp('updated_at')->nullable()->useCurrent();
                }
            });
        }

        // ---- opportunity_applications (ensure status + timestamps exist) ----
        if (Schema::hasTable('opportunity_applications')) {
            Schema::table('opportunity_applications', function (Blueprint $t) {
                if (!Schema::hasColumn('opportunity_applications','status')) $t->string('status', 32)->default('pending')->after('opportunity_id');
                $hasCreated = Schema::hasColumn('opportunity_applications','created_at');
                $hasUpdated = Schema::hasColumn('opportunity_applications','updated_at');
                if (!$hasCreated && !$hasUpdated) {
                    $t->timestamps();
                } elseif (!$hasCreated) {
                    $t->timestamp('created_at')->nullable()->useCurrent();
                } elseif (!$hasUpdated) {
                    $t->timestamp('updated_at')->nullable()->useCurrent();
                }
            });
        }

        // ---- volunteer_hours (ensure hours/note exist) ----
        if (Schema::hasTable('volunteer_hours')) {
            Schema::table('volunteer_hours', function (Blueprint $t) {
                if (!Schema::hasColumn('volunteer_hours','hours')) $t->decimal('hours', 6, 2)->default(0)->after('opportunity_id');
                if (!Schema::hasColumn('volunteer_hours','note'))  $t->text('note')->nullable()->after('hours');
            });
        }
    }

    public function down(): void {
        // No destructive rollback for safety.
    }
};
