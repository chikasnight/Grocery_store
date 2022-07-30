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
        Schema::create('groceries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('price');
            $table->enum('category', ['Diary Products', 'Beverages','Fruits and Vegetables','Non Veg', 'Cookies', 'Cat food','Fast Food', 'Baby Products']);
            $table->string('image')->nullable();
            $table->boolean('upload_successful')->default(false);
            $table->string('disk')->default('public');
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
        Schema::dropIfExists('groceries');
    }
};
