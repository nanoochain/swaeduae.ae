<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKycsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('kycs')) {
            Schema::create('kycs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->unique();
                $table->string('document_path');
                $table->string('status')->default('pending'); // pending, approved, rejected
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }
    public function down()
    {
        Schema::dropIfExists('kycs');
    }
}
