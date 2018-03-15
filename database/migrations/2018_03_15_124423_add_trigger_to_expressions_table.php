<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTriggerToExpressionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expressions', function (Blueprint $table) {

            \Illuminate\Support\Facades\DB::unprepared('
            DROP PROCEDURE IF EXISTS insert_code_expressions;
            CREATE TRIGGER insert_code_expressions BEFORE INSERT ON `expressions` FOR EACH ROW
            BEGIN
            SET NEW.code_expressions = CONCAT(NEW.scale_evaluation_id,NEW.messages_expressions_id);
            END;            
        ');
            \Illuminate\Support\Facades\DB::unprepared('
          DROP PROCEDURE IF EXISTS update_code_expressions;
            CREATE TRIGGER update_code_expressions BEFORE UPDATE ON `expressions` FOR EACH ROW
            BEGIN
            SET NEW.code_expressions = CONCAT(NEW.scale_evaluation_id,NEW.messages_expressions_id);
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
        Schema::table('expressions', function (Blueprint $table) {
            \Illuminate\Support\Facades\DB::unprepared('DROP TRIGGER `insert_code_expressions`');
            \Illuminate\Support\Facades\DB::unprepared('DROP TRIGGER `update_code_expressions`');
        });
    }
}
