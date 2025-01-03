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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('username')->unique()->nullable();
            $table->string('mobileNumber')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('isGoogleAccount')->default(false);
            $table->boolean('isAdmin')->default(false);
            $table->boolean('isFacebookAccount')->default(false);
            $table->string('password')->nullable();
            $table->boolean('isActive')->default(true);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
