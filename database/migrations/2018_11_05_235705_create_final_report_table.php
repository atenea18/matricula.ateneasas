<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinalReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('final_report', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 191)->unique();



            $table->unsignedBigInteger('enrollment_id');
            $table->foreign('enrollment_id')
                ->references('id')->on('enrollment');

            $table->unsignedBigInteger('report_state_id');
            $table->foreign('report_state_id')
                ->references('id')->on('report_state');

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
        Schema::dropIfExists('final_report');
    }
}
