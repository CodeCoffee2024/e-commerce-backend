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
        Schema::create('shipping_matrices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('origin_cityMunicipality_id')->index()->nullable();
            $table->foreign('origin_cityMunicipality_id')->references('id')->on('city_municipalities')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('destination_cityMunicipality_id')->index()->nullable();
            $table->foreign('destination_cityMunicipality_id')->references('id')->on('city_municipalities')->onUpdate('cascade')->onDelete('cascade');
            $table->decimal('fee', 8, 2);
            $table->timestamps();
            $table->unique(['origin_cityMunicipality_id', 'destination_cityMunicipality_id'], 'unique_origin_destination');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipping_matrices');
    }
};
