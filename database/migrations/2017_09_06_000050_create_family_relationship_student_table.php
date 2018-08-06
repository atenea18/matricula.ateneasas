<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFamilyRelationshipStudentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('family_relationship_student', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Relacion con el alumno
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')
                  ->references('id')->on('student')
                  ->onDelete('cascade');

            // Relacion Familia
            $table->unsignedBigInteger('family_id');
            $table->foreign('family_id')
                  ->references('id')->on('family')
                  ->onDelete('cascade');

            // Relacion Parentesco
            $table->unsignedInteger('relationship_id');
            $table->foreign('relationship_id')
                  ->references('id')->on('relationship')
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
        Schema::dropIfExists('family_relationship_student');
    }
}
