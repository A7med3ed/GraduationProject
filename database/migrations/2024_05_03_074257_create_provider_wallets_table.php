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
        Schema::create('provider_wallets', function (Blueprint $table) {
            $table->string('ServiceProviderID')->primary();
            $table->foreign('ServiceProviderID')->references('ServiceProviderID')->on('service_providers')->onDelete('cascade'); // Foreign key referencing ServiceProviderID
            $table->decimal('Balance', 10, 2)->default(0);
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
        Schema::dropIfExists('provider_wallets');
    }
};
