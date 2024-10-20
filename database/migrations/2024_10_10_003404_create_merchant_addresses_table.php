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
            $table->unsignedBigInteger('order_id')->index()->nullable();;
            $table->foreign('barangay_id')->references('id')->on('barangays')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('merchant_id')->index();
            $table->foreign('merchant_id')->references('id')->on('merchants')->onUpdate('cascade')->onDelete('cascade');
            $table->string('zipCode');
            $table->foreign('order_id')
                  ->references('id')
                  ->on('orders')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            $table->boolean('isMainReturnAddress');
            $table->boolean('isMainPickupAddress');
            $table->boolean('isActive');
            $table->timestamps();
        });

        Schema::create('order_shipping_merchants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('merchant_address_id');
            $table->decimal('shipping_cost', 8, 2);  // Shipping cost per merchant for this order
            $table->foreign('order_id')->references('id')->on('orders')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('merchant_address_id')->references('id')->on('merchant_addresses')->onUpdate('cascade')->onDelete('cascade');
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
