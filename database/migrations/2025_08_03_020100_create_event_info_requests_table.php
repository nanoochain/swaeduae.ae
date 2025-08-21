<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventInfoRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('event_info_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->json('form_fields'); // JSON structure for form
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('event_info_requests');
    }
}
