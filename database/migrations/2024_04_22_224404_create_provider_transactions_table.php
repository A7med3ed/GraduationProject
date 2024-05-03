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
        Schema::create('provider_transactions', function (Blueprint $table) {
            $table->string('transaction_id')->primary(); 
            $table->string('sender_id');
            $table->foreign('sender_id')->references('user_id')->on('users')->onDelete('cascade'); 
            $table->string('provider_receiver_id');
            $table->foreign('provider_receiver_id')->references('ServiceProviderID')->on('service_providers')->onDelete('cascade');
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
        Schema::dropIfExists('provider_transactions');
    }
};
