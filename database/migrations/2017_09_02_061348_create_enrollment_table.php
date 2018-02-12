<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnrollmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enrollment', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code')->unique();
            $table->string('folio')->nullable();
            
            // Relacion Estudiante
            $table->unsignedBigInteger('school_year_id');
            $table->foreign('school_year_id')
                  ->references('id')->on('schoolyears')
                  ->onDelete('cascade');

            // Relacion Estudiante
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')
                  ->references('id')->on('student')
                  ->onDelete('cascade');
                  
            // Relacion Salon de clase
            $table->unsignedInteger('garde_id');
            $table->foreign('garde_id')
                  ->references('id')->on('grade')
                  ->onDelete('cascade');

            // Relacion Estado de matricula
            $table->unsignedInteger('enrollment_state_id');
            $table->foreign('enrollment_state_id')
                  ->references('id')->on('enrollment_state')
                  ->onDelete('cascade');

            // Relación con la institución
            $table->unsignedBigInteger('institution_id');
            $table->foreign('institution_id')
                  ->references('id')->on('institution')
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
        Schema::dropIfExists('enrollment');
    }
}
