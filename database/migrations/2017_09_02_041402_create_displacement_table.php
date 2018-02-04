<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDisplacementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('displacement', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('expulsion_date')->nullable();
            $table->string('certificate', 100)->nullable();

            // Relacion Estudiante
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')
                  ->references('id')->on('student')
                  ->onDelete('cascade');

            // Relacion Victima de conflicto
            $table->unsignedInteger('victim_of_conflict_id');
            $table->foreign('victim_of_conflict_id')
                  ->references('id')->on('victim_of_conflict')
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
        Schema::dropIfExists('displacement');
    }
}
