<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (!Schema::hasTable('volunteer_applications')) {
            Schema::create('volunteer_applications', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('event_id')->constrained()->onDelete('cascade');
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('volunteer_applications');
    }
};
