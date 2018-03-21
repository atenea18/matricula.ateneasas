<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesExpressionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages_expressions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code_messages_expressions', 191)->unique();
            $table->text('name');

            $table->unsignedBigInteger('messages_id');

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
        Schema::dropIfExists('messages_expressions');
    }
}
