<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePerformancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('performances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code_performances', 191)->unique();

            $table->unsignedBigInteger('pensum_id');
            $table->foreign('pensum_id')
                ->references('id')->on('pensum');

            $table->unsignedBigInteger('messages_id');
            $table->foreign('messages_id')
                ->references('id')->on('messages');

            $table->unsignedInteger('periods_id');
            $table->foreign('periods_id')
                ->references('id')->on('periods');

            $table->unsignedBigInteger('evaluation_parameters_id');
            $table->foreign('evaluation_parameters_id')
                ->references('id')->on('evaluation_parameters');

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
        Schema::dropIfExists('performances');
    }
}
