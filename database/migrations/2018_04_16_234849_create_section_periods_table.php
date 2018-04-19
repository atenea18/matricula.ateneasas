<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSectionPeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section_periods', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 191)->unique();
            $table->float('percent');
            $table->dateTime('start_date');
            $table->dateTime('end_date');

            $table->unsignedInteger('section_id');
            $table->foreign('section_id')
                ->references('id')->on('section');

            $table->unsignedInteger('periods_id');
            $table->foreign('periods_id')
                ->references('id')->on('periods');

            $table->unsignedBigInteger('school_year_id');
            $table->foreign('school_year_id')
                ->references('id')->on('schoolyears');

            $table->unsignedInteger('periods_state_id');
            $table->foreign('periods_state_id')
                ->references('id')->on('periods_state');

            $table->timestamps();
        });

        Schema::table('section_periods', function (Blueprint $table) {
            //Disparadores
            \Illuminate\Support\Facades\DB::unprepared('
                DROP PROCEDURE IF EXISTS insert_code_section_periods;
                CREATE TRIGGER insert_code_section_periods BEFORE INSERT ON `section_periods` FOR EACH ROW
                BEGIN
                SET NEW.code = CONCAT(NEW.section_id,"-",NEW.periods_id,NEW.school_year_id);
                END;
                
            ');
            \Illuminate\Support\Facades\DB::unprepared('
              DROP PROCEDURE IF EXISTS update_code_section_periods;
                CREATE TRIGGER update_code_section_periods BEFORE UPDATE ON `section_periods` FOR EACH ROW
                BEGIN
                SET NEW.code = CONCAT(NEW.section_id,"-",NEW.periods_id,NEW.school_year_id);
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
        Schema::dropIfExists('section_periods');
    }
}
