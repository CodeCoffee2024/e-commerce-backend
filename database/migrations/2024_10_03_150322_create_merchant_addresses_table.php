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
        Schema::create('merchant_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('blockLotFloorBuildingName');
            $table->string('streetAddress');
            $table->unsignedBigInteger('barangay_id')->index()->nullable();
            $table->foreign('barangay_id')->references('id')->on('barangays')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('merchant_id')->index();
            $table->foreign('merchant_id')->references('id')->on('merchants')->onUpdate('cascade')->onDelete('cascade');
            $table->string('zipCode');
            $table->boolean('isMainReturnAddress');
            $table->boolean('isMainPickupAddress');
            $table->boolean('isActive');
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
        Schema::dropIfExists('merchant_addresses');
    }
};
