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
        Schema::table('working_day_periods', function (Blueprint $table) {
            
            \Illuminate\Support\Facades\DB::unprepared('
                DROP PROCEDURE IF EXISTS insert_code_working_day_periods;
                CREATE TRIGGER insert_code_working_day_periods BEFORE INSERT ON `working_day_periods` FOR EACH ROW
                BEGIN
                SET NEW.code_working_day_periods = CONCAT(NEW.working_day_id,NEW.periods_id,NEW.periods_state_id,NEW.institution_id,NEW.school_year_id);
                END;
                
            ');
                \Illuminate\Support\Facades\DB::unprepared('
              DROP PROCEDURE IF EXISTS update_code_working_day_periods;
                CREATE TRIGGER update_code_working_day_periods BEFORE UPDATE ON `working_day_periods` FOR EACH ROW
                BEGIN
                SET NEW.code_working_day_periods = CONCAT(NEW.working_day_id,NEW.periods_id,NEW.periods_state_id,NEW.institution_id,NEW.school_year_id);
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
        Schema::table('working_day_periods', function (Blueprint $table) {
            \Illuminate\Support\Facades\DB::unprepared('DROP TRIGGER `insert_code_working_day_periods`');
            \Illuminate\Support\Facades\DB::unprepared('DROP TRIGGER `update_code_working_day_periods`');
        });
    }
}
