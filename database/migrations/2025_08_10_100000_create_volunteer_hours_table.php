<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('volunteer_hours')) {
            Schema::create('volunteer_hours', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('opportunity_id');
                $table->integer('minutes')->default(0);
                $table->timestamps();

                $table->unique(['user_id','opportunity_id']);
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('opportunity_id')->references('id')->on('opportunities')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('volunteer_hours');
    }
};
