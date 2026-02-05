<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class VuaTiengViet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vua_tieng_viet', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cauhoi');
            $table->string('cautraloi');
            $table->string('ghiChu')->nullable();
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
        Schema::dropIfExists('vua_tieng_viet');
    }
}
