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
        Schema::create('donations', function (Blueprint $table) {
            $table->string('Service_id')->primary();
            $table->string('ServiceProviderID');
            $table->foreign('ServiceProviderID')->references('ServiceProviderID')->on('service_providers')->onDelete('cascade');
            $table->string('Support_Number');
            $table->string('Donation_Purpose');
            $table->string('Address');
            $table->json('extra_fields')->nullable();
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
        Schema::dropIfExists('donations');
    }
};
