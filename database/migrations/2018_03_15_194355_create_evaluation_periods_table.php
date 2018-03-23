<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEvaluationPeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluation_periods', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code_evaluation_periods', 191)->unique();

            $table->unsignedBigInteger('enrollment_id');
            $table->foreign('enrollment_id')
                ->references('id')->on('enrollment');

            $table->unsignedInteger('periods_id');
            $table->foreign('periods_id')
                ->references('id')->on('periods');

            $table->unsignedBigInteger('asignatures_id');
            $table->foreign('asignatures_id')
                ->references('id')->on('asignatures');

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
        Schema::dropIfExists('evaluation_periods');
    }
}
