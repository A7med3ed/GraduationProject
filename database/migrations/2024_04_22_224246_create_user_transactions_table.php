<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_transactions', function (Blueprint $table) {
            $table->string('transaction_id')->primary(); 
            $table->string('sender_id');
            $table->foreign('sender_id')->references('user_id')->on('users')->onDelete('cascade'); 
            $table->string('user_receiver_id');
            $table->foreign('user_receiver_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_transactions');
    }
};
