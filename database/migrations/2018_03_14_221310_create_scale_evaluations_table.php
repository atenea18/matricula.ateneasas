<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScaleEvaluationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scale_evaluations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 191);
            $table->string('abbreviation', 45);
            $table->double('rank_start');
            $table->double('rank_end');

            $table->unsignedBigInteger('institution_id');
            $table->foreign('institution_id')
                ->references('id')->on('institution');

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
        Schema::dropIfExists('scale_evaluations');
    }
}
