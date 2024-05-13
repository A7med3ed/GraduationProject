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
        Schema::create('provider_bank_transactions', function (Blueprint $table) {
            $table->string('transaction_id')->primary(); 
            $table->string('ServiceProviderID');
            $table->foreign('ServiceProviderID')->references('ServiceProviderID')->on('service_providers')->onDelete('cascade'); 
            $table->string('bank_account_number');
            $table->string('bank_name');
            $table->string('receiver_name');
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
        Schema::dropIfExists('provider_bank_transactions');
    }
};