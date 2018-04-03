<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEvaluationTypeIdToEvaluationsParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('evaluation_parameters', function (Blueprint $table) {
            $table->unsignedInteger('evaluation_type_id')->nullable();
            $table->foreign('evaluation_type_id')
                ->references('id')->on('evaluation_type');
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
            $table->dropForeign(['evaluation_type_id']);
            $table->dropColumn(['evaluation_type_id']);
        });
    }
}
