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
        Schema::create('city_municipalities', function (Blueprint $table) {
            $table->id();
            $table->string('psgcCode');
            $table->string('description');
            $table->string('provincialCode');
            $table->string('regionCode');
            $table->string('code');
            $table->boolean('isActive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('city_municipalities');
    }
};
