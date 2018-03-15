<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTriggerToPerformancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('performances', function (Blueprint $table) {
            \Illuminate\Support\Facades\DB::unprepared('
            DROP PROCEDURE IF EXISTS insert_code_performances;
            CREATE TRIGGER insert_code_performances BEFORE INSERT ON `performances` FOR EACH ROW
            BEGIN
            SET NEW.code_performances = CONCAT(NEW.pensum_id,NEW.evaluation_parameters_id, NEW.messages_expressions_id);
            END;
            
        ');
            \Illuminate\Support\Facades\DB::unprepared('
          DROP PROCEDURE IF EXISTS update_code_performances;
            CREATE TRIGGER update_code_performances BEFORE UPDATE ON `performances` FOR EACH ROW
            BEGIN
            SET NEW.code_performances = CONCAT(NEW.pensum_id,NEW.evaluation_parameters_id,NEW.messages_expressions_id);
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
    }
}
