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
        Schema::create('ticket_bookings', function (Blueprint $table) {
            $table->string('Service_id')->primary();
            $table->string('ServiceProviderID');
            $table->foreign('ServiceProviderID')->references('ServiceProviderID')->on('service_providers')->onDelete('cascade');
            $table->string('Support_Contact_Number');
            $table->string('EventName');
            $table->date('EventDate');
            $table->integer('NumberofTickets');
            $table->decimal('Price', 10, 2);
            $table->string('Place');
            $table->longtext('icon')->nullable(); 
            $table->json('extra_fields')->nullable(); // Dynamic fields stored as JSON
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
        Schema::dropIfExists('ticket_bookings');
    }
};