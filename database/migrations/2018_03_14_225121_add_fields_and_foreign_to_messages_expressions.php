<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsAndForeignToMessagesExpressions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('messages_expressions', function (Blueprint $table) {
            $table->text('reinforcement');
            $table->text('recommendation');

            $table->foreign('messages_id')
                ->references('id')->on('messages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('messages_expressions', function (Blueprint $table) {
            $table->dropColumn(['reinforcement', 'recommendation']);
            $table->dropForeign(['messages_id']);
        });
    }
}
