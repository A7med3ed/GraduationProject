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
        Schema::create('mobile_internet_bills', function (Blueprint $table) {
            $table->string('Service_id')->primary();
            $table->string('ServiceProviderID');
            $table->foreign('ServiceProviderID')->references('ServiceProviderID')->on('service_providers')->onDelete('cascade');
            $table->string('Support_Contact_Number');
            $table->string('Mobile_code');
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
        Schema::dropIfExists('mobile_internet_bills');
    }
};
