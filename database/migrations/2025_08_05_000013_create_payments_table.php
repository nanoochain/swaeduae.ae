<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('payment_method'); // stripe, paytabs, etc
                $table->string('transaction_id')->unique();
                $table->decimal('amount', 10, 2);
                $table->string('status'); // pending, completed, failed
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
