<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotesParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notes_parameters', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code_notes_parameters', 191)->unique();
            $table->string('name');
            $table->float('percent');

            $table->unsignedBigInteger('evaluation_parameters_id');
            $table->foreign('evaluation_parameters_id')
                ->references('id')->on('evaluation_parameters');

            $table->unsignedInteger('notes_type_id');
            $table->foreign('notes_type_id')
                ->references('id')->on('notes_type');

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
        Schema::dropIfExists('notes_parameters');
    }
}
