<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIdentificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('identification', function (Blueprint $table) {
            $table->bigIncrements('id');
            // $table->string('state')->nullable();
            $table->string('identification_number');
            $table->date('birthdate')->nullable();

            // Relacion Tipo de identificación
            $table->unsignedInteger('identification_type_id');
            $table->foreign('identification_type_id')
                  ->references('id')->on('identification_type')
                  ->onDelete('cascade');

            // Relacion Tipo de la ciudad de expedicion del documento de identidad
            $table->unsignedInteger('id_city_expedition')->nullable();
            $table->foreign('id_city_expedition')
                  ->references('id')->on('city')
                  ->onDelete('cascade');

            // Relacion del Genero
            $table->unsignedInteger('gender_id');
            $table->foreign('gender_id')
                  ->references('id')->on('gender')
                  ->onDelete('cascade');

            // Relacion de la ciudad de nacimiento
            $table->unsignedInteger('id_city_of_birth')->nullable();
            $table->foreign('id_city_of_birth')
                  ->references('id')->on('city')
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
        Schema::dropIfExists('identification');
    }
}
