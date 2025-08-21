<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('org_kyc')) {
            Schema::create('org_kyc', function (Blueprint $t) {
                $t->id();
                $t->unsignedBigInteger('organization_id');
                $t->string('status')->default('pending');
                $t->string('file_path')->nullable();
                $t->timestamp('submitted_at')->nullable();
                $t->timestamp('reviewed_at')->nullable();
                $t->unsignedBigInteger('reviewed_by')->nullable();
                $t->text('review_note')->nullable();
                $t->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('org_kyc');
    }
};
