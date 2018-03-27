<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnPeriodsStateIdToWorkingDayPeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('working_day_periods', function (Blueprint $table) {
            $table->unsignedInteger('periods_state_id');
            $table->foreign('periods_state_id')
                ->references('id')->on('period_states');
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
            $table->dropForeign(['periods_state_id']);
            $table->dropColumn(['periods_state_id']);
        });
    }
}
