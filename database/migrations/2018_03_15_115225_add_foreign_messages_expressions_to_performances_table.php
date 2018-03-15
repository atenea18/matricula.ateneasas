<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignMessagesExpressionsToPerformancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('performances', function (Blueprint $table) {

            $table->dropForeign(['messages_id']);
            $table->dropColumn(['messages_id']);

            $table->unsignedBigInteger('messages_expressions_id')->after('evaluation_parameters_id');
            $table->foreign('messages_expressions_id')
                ->references('id')->on('messages_expressions');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('performances', function (Blueprint $table) {
            $table->dropForeign(['messages_expressions_id']);
            $table->dropColumn(['messages_expressions_id']);
        });
    }
}
