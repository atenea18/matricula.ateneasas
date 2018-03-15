<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignToEvaluationParameters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('evaluation_parameters', function (Blueprint $table) {
            $table->foreign('institution_id')
                ->references('id')->on('institution');

            // Relacion año lectivo
            $table->foreign('school_year_id')
                ->references('id')->on('schoolyears');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('evaluation_parameters', function (Blueprint $table) {
            $table->dropForeign(['institution_id']);
            $table->dropForeign(['school_year_id']);
        });
    }
}
