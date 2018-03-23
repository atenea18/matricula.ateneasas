<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeriodWorkingDayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('period_working_day', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->unique();
            $table->double('percent')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->unsignedInteger('working_day_id');
            $table->foreign('working_day_id')
                ->references('id')->on('working_day');

            $table->unsignedBigInteger('period_id');
            $table->foreign('period_id')
                ->references('id')->on('periods');

            $table->unsignedBigInteger('period_state_id');
            $table->foreign('period_state_id')
                ->references('id')->on('period_states');

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
        Schema::dropIfExists('period_working_day');
    }
}
