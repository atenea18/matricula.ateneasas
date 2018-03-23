<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTriggerPeriodWorkingDayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('period_working_day', function (Blueprint $table) {
            
            \Illuminate\Support\Facades\DB::unprepared('
                DROP PROCEDURE IF EXISTS insert_code_period_working_day;
                CREATE TRIGGER insert_code_period_working_day BEFORE INSERT ON `period_working_day` FOR EACH ROW
                BEGIN
                SET NEW.code = CONCAT(NEW.working_day_id,NEW.period_id,NEW.period_state_id,NEW.institution_id,NEW.school_year_id);
                END;
                
            ');
                \Illuminate\Support\Facades\DB::unprepared('
              DROP PROCEDURE IF EXISTS update_code_period_working_day;
                CREATE TRIGGER update_code_period_working_day BEFORE UPDATE ON `period_working_day` FOR EACH ROW
                BEGIN
                SET NEW.code = CONCAT(NEW.working_day_id,NEW.period_id,NEW.period_state_id,NEW.institution_id,NEW.school_year_id);
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
        Schema::table('period_working_day', function (Blueprint $table) {
            \Illuminate\Support\Facades\DB::unprepared('DROP TRIGGER `insert_code_period_working_day`');
            \Illuminate\Support\Facades\DB::unprepared('DROP TRIGGER `update_code_period_working_day`');
        });
    }
}
