<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('address', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('address')->nullable();
            $table->string('neighborhood')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobil')->nullable();
            $table->string('email')->nullable();

            // Relacion Ciudad
            $table->unsignedInteger('id_city_address')->nullable();
            $table->foreign('id_city_address')
                  ->references('id')->on('city')
                  ->onDelete('cascade');

            // Relacion Zona rural
            $table->unsignedInteger('zone_id')->nullable();
            $table->foreign('zone_id')
                  ->references('id')->on('zones')
                  ->onDelete('cascade');

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
        Schema::dropIfExists('address');
    }
}
