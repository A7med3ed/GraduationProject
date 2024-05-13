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
        Schema::create('mobile_recharges', function (Blueprint $table) {
            $table->id('Service_id');
            $table->string('ServiceProviderID');
            $table->foreign('ServiceProviderID')->references('ServiceProviderID')->on('service_providers')->onDelete('cascade'); 
            $table->string('Support_Contact_Number');
            $table->decimal('Min_Recharge', 8, 2);
            $table->decimal('Max_Recharge', 8, 2);
            $table->string('Mobile_code');
            $table->longtext('icon')->nullable(); 
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
        Schema::dropIfExists('mobile_recharges');
    }
};