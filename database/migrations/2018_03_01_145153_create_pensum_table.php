<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePensumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pensum', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code_pensum', 191)->unique();
            $table->integer('percent')->nullable();
            $table->text('description')->nullable();
            $table->integer('ihs')->nullable();
            $table->integer('order')->nullable();

            // Relación Grado
            $table->unsignedInteger('grade_id');
            $table->foreign('grade_id')
                ->references('id')->on('grade');

            $table->unsignedBigInteger('areas_id');
            $table->foreign('areas_id')
                ->references('id')->on('areas');

            $table->unsignedBigInteger('subjects_type_id');
            $table->foreign('subjects_type_id')
                ->references('id')->on('subjects_type');

            $table->unsignedBigInteger('asignatures_id');
            $table->foreign('asignatures_id')
                ->references('id')->on('asignatures');

            $table->unsignedBigInteger('institution_id');
            $table->foreign('institution_id')
                ->references('id')->on('institution');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pensum');
    }
}
