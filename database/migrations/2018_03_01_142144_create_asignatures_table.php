<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsignaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asignatures', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('abbreviation');
            $table->unsignedBigInteger('subjects_type_id');
            $table->timestamps();
            $table->foreign('subjects_type_id')
                ->references('id')->on('subjects_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asignatures');
    }
}
