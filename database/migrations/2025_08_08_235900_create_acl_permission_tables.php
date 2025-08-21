<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('acl_permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
        });

        Schema::create('acl_roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
        });

        Schema::create('acl_model_has_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->index(['model_id', 'model_type'], 'acl_mhp_model_id_model_type_index');

            $table->foreign('permission_id')->references('id')->on('acl_permissions')->onDelete('cascade');
            $table->primary(['permission_id', 'model_id', 'model_type'], 'acl_model_has_permissions_primary');
        });

        Schema::create('acl_model_has_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->index(['model_id', 'model_type'], 'acl_mhr_model_id_model_type_index');

            $table->foreign('role_id')->references('id')->on('acl_roles')->onDelete('cascade');
            $table->primary(['role_id', 'model_id', 'model_type'], 'acl_model_has_roles_primary');
        });

        Schema::create('acl_role_has_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('role_id');

            $table->foreign('permission_id')->references('id')->on('acl_permissions')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('acl_roles')->onDelete('cascade');

            $table->primary(['permission_id', 'role_id'], 'acl_role_has_permissions_primary');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('acl_role_has_permissions');
        Schema::dropIfExists('acl_model_has_roles');
        Schema::dropIfExists('acl_model_has_permissions');
        Schema::dropIfExists('acl_roles');
        Schema::dropIfExists('acl_permissions');
    }
};
