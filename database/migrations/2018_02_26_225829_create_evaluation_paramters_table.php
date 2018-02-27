<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEvaluationParamtersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluation_paramters', function (Blueprint $table) {
            $table->increments('id');
            // $table->string('code')->nullable();
            $table->string('parameter');
            $table->string('abbreviation');
            $table->float('percent');

            // Relación con la institución
            $table->unsignedBigInteger('institution_id');
            $table->foreign('institution_id')
                  ->references('id')->on('institution')
                  ->onDelete('cascade');

            // Relacion año lectivo
            $table->unsignedBigInteger('school_year_id');
            $table->foreign('school_year_id')
                  ->references('id')->on('schoolyears')
                  ->onDelete('cascade');

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
        Schema::dropIfExists('evaluation_paramters');
    }
}
