<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotesParametersPerformancesSubTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notes_parameters_performances_sub', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 191)->unique();

            $table->unsignedBigInteger('notes_parameters_id');
            $table->foreign('notes_parameters_id')
                ->references('id')->on('notes_parameters');

            $table->unsignedBigInteger('performances_id');
            $table->foreign('performances_id')
                ->references('id')->on('performances');

            $table->unsignedBigInteger('sub_group_pensum_id');
            $table->foreign('sub_group_pensum_id')
                ->references('id')->on('sub_group_pensum');

            $table->unsignedInteger('periods_id');
            $table->foreign('periods_id')
                ->references('id')->on('periods');
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
        Schema::dropIfExists('notes_parameters_performances_sub');
    }
}
