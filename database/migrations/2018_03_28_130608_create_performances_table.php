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
            $table->string('code', 191)->unique();

            $table->unsignedBigInteger('pensum_id');
            $table->foreign('pensum_id')
                ->references('id')->on('pensum');

            $table->unsignedInteger('periods_id');
            $table->foreign('periods_id')
                ->references('id')->on('periods');

            $table->unsignedBigInteger('evaluation_parameters_id');
            $table->foreign('evaluation_parameters_id')
                ->references('id')->on('evaluation_parameters');

            $table->unsignedBigInteger('messages_expressions_id');
            $table->foreign('messages_expressions_id')
                ->references('id')->on('messages_expressions');

            $table->timestamps();


        });

        Schema::table('performances', function (Blueprint $table) {
            //Disparadores
            \Illuminate\Support\Facades\DB::unprepared('
                DROP PROCEDURE IF EXISTS insert_code_performances;
                CREATE TRIGGER insert_code_performances BEFORE INSERT ON `performances` FOR EACH ROW
                BEGIN
                SET NEW.code = CONCAT(NEW.pensum_id,NEW.evaluation_parameters_id,NEW.messages_expressions_id);
                END;
                
            ');
            \Illuminate\Support\Facades\DB::unprepared('
              DROP PROCEDURE IF EXISTS update_code_performances;
                CREATE TRIGGER update_code_performances BEFORE UPDATE ON `performances` FOR EACH ROW
                BEGIN
                SET NEW.code = CONCAT(NEW.pensum_id,NEW.evaluation_parameters_id,NEW.messages_expressions_id);
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
        Schema::table('performances', function (Blueprint $table) {
            \Illuminate\Support\Facades\DB::unprepared('DROP TRIGGER `insert_code_performances`');
            \Illuminate\Support\Facades\DB::unprepared('DROP TRIGGER `update_code_performances`');
        });

        Schema::dropIfExists('performances');
    }
}
