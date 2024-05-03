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
        Schema::create('card_details', function (Blueprint $table) {
            $table->string('card_id')->primary();
            $table->string('card_holder_name');
            $table->enum('card_type', ['debit', 'credit']);
            $table->string('card_number');
            $table->string('expiry_date');
            $table->string('cvv');
            $table->string('user_id');
            $table->foreign('user_id')->references('user_id')->on('bank_accounts')->onDelete('cascade');
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
        Schema::dropIfExists('card_details');
    }
};
