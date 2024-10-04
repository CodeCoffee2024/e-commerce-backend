<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->unsignedBigInteger('category_id')->index()->nullable();
            $table->unsignedBigInteger('merchant_id')->index()->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('merchant_id')->references('id')->on('merchants')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('pickup_address_id')->nullable();
            $table->unsignedBigInteger('return_address_id')->nullable();
            $table->foreign('pickup_address_id')->references('id')->on('merchant_addresses')->onDelete('set null');
            $table->foreign('return_address_id')->references('id')->on('merchant_addresses')->onDelete('set null');

            
            $table->decimal('price', 8, 2);
            // $table->unsignedBigInteger('parent_product_id')->index()->nullable();
            // $table->foreign('parent_product_id')->references('id')->on('products')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('quantity');
            $table->string('imgPath');
            $table->boolean('isActive');
            $table->timestamps();
        });
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
