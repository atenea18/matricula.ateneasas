<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTerritorialtyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('territorialty', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('guard', 100)->nullable();
            $table->string('ethnicity', 100)->nullable();
            
            // Relacion Estudiante
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')
                  ->references('id')->on('student')
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
        Schema::dropIfExists('territorialty');
    }
}
