<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNoAttendanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('no_attendance', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('quantity');

            $table->unsignedBigInteger('evaluation_periods_id');
            $table->foreign('evaluation_periods_id')
                ->references('id')->on('evaluation_periods');

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
        Schema::dropIfExists('no_attendance');
    }
}
