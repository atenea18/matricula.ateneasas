<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnrollmentNoveltyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('enrollment_novelty', function (Blueprint $table) {
        //     $table->increments('id');

        //     // Relacion Novedad
        //     $table->integer('novelty_id')->unsigned();
        //     $table->foreign('novelty_id')
        //           ->references('id')->on('novelty')
        //           ->onDelete('cascade');

        //     // Relacion Matricula
        //     $table->integer('enrollment_id')->unsigned();
        //     $table->foreign('enrollment_id')
        //           ->references('id')->on('enrollment')
        //           ->onDelete('cascade');

        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enrollment_novelty');
    }
}
