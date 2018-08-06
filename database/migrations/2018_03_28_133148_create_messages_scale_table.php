<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesScaleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages_scale', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 191)->unique();
            $table->text('name')->nullable();
            $table->unsignedBigInteger('scale_evaluations_id');
            $table->foreign('scale_evaluations_id')
                ->references('id')->on('scale_evaluations');

            $table->unsignedBigInteger('messages_expressions_id');
            $table->foreign('messages_expressions_id')
                ->references('id')->on('messages_expressions');

            $table->timestamps();
        });

        Schema::table('messages_scale', function (Blueprint $table) {
            //Disparadores
            \Illuminate\Support\Facades\DB::unprepared('
                DROP PROCEDURE IF EXISTS insert_code_messages_scale;
                CREATE TRIGGER insert_code_messages_scale BEFORE INSERT ON `messages_scale` FOR EACH ROW
                BEGIN
                SET NEW.code = CONCAT(NEW.scale_evaluations_id,NEW.messages_expressions_id);
                END;
                
            ');
            \Illuminate\Support\Facades\DB::unprepared('
              DROP PROCEDURE IF EXISTS update_code_messages_scale;
                CREATE TRIGGER update_code_messages_scale BEFORE UPDATE ON `messages_scale` FOR EACH ROW
                BEGIN
                SET NEW.code = CONCAT(NEW.scale_evaluations_id,NEW.messages_expressions_id);
                END;
                
            ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('messages_scale', function (Blueprint $table) {
            \Illuminate\Support\Facades\DB::unprepared('DROP TRIGGER `insert_code_messages_scale`');
            \Illuminate\Support\Facades\DB::unprepared('DROP TRIGGER `update_code_messages_scale`');
        });

        Schema::dropIfExists('messages_scale');
    }
}
