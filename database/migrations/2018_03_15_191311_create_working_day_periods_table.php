<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkingDayPeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('working_day_periods', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code_working_day_periods', 191)->unique();
            $table->float('percent');
            $table->dateTime('start_date');
            $table->dateTime('end_date');

            $table->unsignedInteger('working_day_id');
            $table->foreign('working_day_id')
                ->references('id')->on('working_day');

            $table->unsignedInteger('periods_id');
            $table->foreign('periods_id')
                ->references('id')->on('periods');

            $table->unsignedBigInteger('institution_id');
            $table->foreign('institution_id')
                ->references('id')->on('institution');

            $table->unsignedBigInteger('school_year_id');
            $table->foreign('school_year_id')
                ->references('id')->on('schoolyears');


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
        Schema::dropIfExists('working_day_periods');
    }
}
