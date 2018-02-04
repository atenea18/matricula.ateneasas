<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFamilyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('family', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 45);
            $table->string('last_name', 45);

            // Relacion Estudiante
            // $table->integer('student_id')->unsigned();
            // $table->foreign('student_id')
            //       ->references('id')->on('student')
            //       ->onDelete('cascade');

            // Relacion Identificación
            $table->unsignedBigInteger('identification_id');
            $table->foreign('identification_id')
                  ->references('id')->on('identification')
                  ->onDelete('cascade');

            // Relacion Dirección
            $table->unsignedBigInteger('address_id');
            $table->foreign('address_id')
                  ->references('id')->on('address')
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
        Schema::dropIfExists('family');
    }
}
