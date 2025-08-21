<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('organization_users')) {
            Schema::create('organization_users', function (Blueprint $t) {
                $t->id();
                $t->unsignedBigInteger('organization_id');
                $t->unsignedBigInteger('user_id');
                $t->string('role')->default('org_manager'); // org_owner|org_manager
                $t->timestamps();
                $t->unique(['organization_id','user_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('organization_users');
    }
};
