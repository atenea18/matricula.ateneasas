<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpressionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expressions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code_expressions', 191)->unique();

            $table->unsignedBigInteger('scale_evaluation_id');
            $table->foreign('scale_evaluation_id')
                ->references('id')->on('scale_evaluations');

            $table->unsignedBigInteger('messages_expressions_id');
            $table->foreign('messages_expressions_id')
                ->references('id')->on('messages_expressions');

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
        Schema::dropIfExists('expressions');
    }
}
