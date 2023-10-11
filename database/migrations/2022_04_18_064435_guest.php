<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Guest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guest', function (Blueprint $table) {
            $table->increments('id');
            $table->string('hoTen')->nullable();
            $table->string('dienThoai')->nullable();
            $table->string('bienSoXe')->unique();
            $table->string('diaChi')->nullable();
            $table->string('quaTang')->nullable();
            $table->string('dapAn')->nullable();
            $table->boolean('isPoll')->default(false);
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
        Schema::dropIfExists('guest');
    }
}
