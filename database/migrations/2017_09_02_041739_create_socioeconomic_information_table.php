<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocioeconomicInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('socioeconomic_information', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('sisben_number')->nullable();
            $table->integer('sisben_level')->nullable();
            $table->boolean('amcf')->nullable()->default(false); // Alumno Madre Cabeza de Familia
            $table->boolean('bhdmcf')->nullable()->default(false); // Beneficiarios Hijos dependientes de madres cabezas de familia
            $table->boolean('bvfp')->nullable()->default(false); // Beneficiario Veterano Fuerza Pública
            $table->boolean('bhn')->nullable()->default(false); // Beneficiario Héroe Nación

            // Relacion Estudiante
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')
                  ->references('id')->on('student')
                  ->onDelete('cascade');

            // Relacion Estrato
            $table->unsignedInteger('stratum_id')->nullable();
            $table->foreign('stratum_id')
                  ->references('id')->on('stratum')
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
        Schema::dropIfExists('socioeconomic_information');
    }
}
