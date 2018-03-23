<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotesParametersCriteriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notes_parameters_criterias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code_notes_criterias', 191)->unique();

            $table->unsignedBigInteger('notes_parameters_id');
            $table->foreign('notes_parameters_id')
                ->references('id')->on('notes_parameters');

            $table->unsignedBigInteger('criterias_id');
            $table->foreign('criterias_id')
                ->references('id')->on('criterias');

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
        Schema::dropIfExists('notes_parameters_criterias');
    }
}
