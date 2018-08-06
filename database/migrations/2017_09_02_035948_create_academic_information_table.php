<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcademicInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academic_information', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('has_subsidy');

            // Relacion Estudiante
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')
                  ->references('id')->on('student')
                  ->onDelete('cascade');

            // Relacion caracter
            $table->unsignedInteger('academic_character_id');
            $table->foreign('academic_character_id')
                  ->references('id')->on('academic_character')
                  ->onDelete('cascade');

            // Relacion especialidad
            $table->unsignedInteger('academic_specialty_id');
            $table->foreign('academic_specialty_id')
                  ->references('id')->on('academic_specialty')
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
        Schema::dropIfExists('academic_information');
    }
}
